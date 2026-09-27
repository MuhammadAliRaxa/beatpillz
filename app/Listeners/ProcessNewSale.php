<?php

namespace App\Listeners;

use App\Events\SaleCreated;
use App\Handlers\SupportHandler;
use App\Jobs\SendPurchaseConfirmationNotification;
use App\Models\Purchase;
use App\Models\ReferralEarning;
use App\Models\Statement;
use Str;

class ProcessNewSale
{
    public function handle(SaleCreated $event)
    {
        $sale = $event->sale;
        $trx = $event->transaction;
        $support = $event->support;

        $author = $sale->author;
        $user = $sale->user;
        $item = $sale->item;

        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->author_id = $author->id;
        $purchase->sale_id = $sale->id;
        $purchase->item_id = $item->id;
        $purchase->license_type = $sale->license_type;
        $purchase->code = Str::uuid()->toString();
        $purchase->save();

        $author->increment('balance', $sale->author_earning);
        $author->increment('total_sales');
        $author->increment('total_sales_amount', $sale->price);

        $statement1 = new Statement();
        $statement1->user_id = $user->id;
        $statement1->title = translate('[Purchase] #:id (:item_name)', [
            'id' => $purchase->id,
            'item_name' => $item->name,
        ]);
        $statement1->amount = $sale->price;
        $statement1->total = $sale->price;
        $statement1->type = Statement::TYPE_DEBIT;
        $statement1->save();

        $buyerTax = $sale->buyer_tax;
        if ($buyerTax) {
            $amount = ($sale->price * $buyerTax->rate) / 100;
            $statement2 = new Statement();
            $statement2->user_id = $user->id;
            $statement2->title = translate('[:tax_name (:tax_rate%)] Purchase #:id (:item_name)', [
                'id' => $purchase->id,
                'item_name' => $item->name,
                'tax_name' => $buyerTax->name,
                'tax_rate' => $buyerTax->rate,
            ]);
            $statement2->amount = $amount;
            $statement2->total = $amount;
            $statement2->type = Statement::TYPE_DEBIT;
            $statement2->save();
        }

        $authorTax = $sale->author_tax;
        $authorEarning = $authorTax ? ($sale->author_earning + $authorTax->amount) : $sale->author_earning;

        $statement3 = new Statement();
        $statement3->user_id = $author->id;
        $statement3->title = translate('[Sale] #:id (:item_name)', [
            'id' => $sale->id,
            'item_name' => $item->name,
        ]);
        $statement3->amount = $sale->price;
        $statement3->buyer_fee = $sale->buyer_fee;
        $statement3->author_fee = $sale->author_fee;
        $statement3->total = $authorEarning;
        $statement3->type = Statement::TYPE_CREDIT;
        $statement3->save();

        if ($authorTax) {
            $statement4 = new Statement();
            $statement4->user_id = $author->id;
            $statement4->title = translate('[:tax_name (:tax_rate%)] Sale #:id (:item_name)', [
                'id' => $sale->id,
                'item_name' => $item->name,
                'tax_name' => $authorTax->name,
                'tax_rate' => $authorTax->rate,
            ]);
            $statement4->amount = $authorTax->amount;
            $statement4->total = $authorTax->amount;
            $statement4->type = Statement::TYPE_DEBIT;
            $statement4->save();
        }

        app(SupportHandler::class)->create($purchase, $trx, $support);

        if (@settings('referral')->status) {
            $referral = $user->referral;
            if ($referral) {
                $referralAuthor = $referral->author;
                $referralEarningAmount = ($sale->price * @settings('referral')->percentage) / 100;

                $referralEarning = new ReferralEarning();
                $referralEarning->referral_id = $referral->id;
                $referralEarning->author_id = $referralAuthor->id;
                $referralEarning->sale_id = $sale->id;
                $referralEarning->author_earning = $referralEarningAmount;
                $referralEarning->save();

                $referral->increment('earnings', $referralEarningAmount);
                $referralAuthor->increment('balance', $referralEarningAmount);
                $referralAuthor->increment('total_referrals_earnings', $referralEarningAmount);

                $statement5 = new Statement();
                $statement5->user_id = $referralAuthor->id;
                $statement5->title = translate('[Referral Earnings] #:id', ['id' => $referralEarning->id]);
                $statement5->amount = $referralEarningAmount;
                $statement5->total = $referralEarningAmount;
                $statement5->type = Statement::TYPE_CREDIT;
                $statement5->save();
            }
        }

        $item->increment('total_sales');
        $item->increment('total_sales_amount', $sale->price);
        $item->increment('total_earnings', $authorEarning);

        dispatch(new SendPurchaseConfirmationNotification($purchase));
    }
}