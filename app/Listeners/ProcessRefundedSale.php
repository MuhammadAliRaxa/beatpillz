<?php

namespace App\Listeners;

use App\Events\SaleRefunded;
use App\Handlers\SupportHandler;
use App\Models\Purchase;
use App\Models\ReferralEarning;
use App\Models\Sale;
use App\Models\Statement;

class ProcessRefundedSale
{
    public function handle(SaleRefunded $event)
    {
        $sale = $event->sale;

        $author = $sale->author;
        $user = $sale->user;
        $item = $sale->item;
        $purchase = $sale->purchase;
        $referralEarning = $sale->referralEarning;

        $sale->status = Sale::STATUS_REFUNDED;
        $sale->update();

        $author->decrement('balance', $sale->author_earning);
        $author->decrement('total_sales');
        $author->decrement('total_sales_amount', $sale->price);

        $user->increment('balance', $sale->price);

        $purchaseStatement = new Statement();
        $purchaseStatement->user_id = $user->id;
        $purchaseStatement->title = translate('[Refund] Purchase #:id (:item_name)', [
            'id' => $purchase->id,
            'item_name' => $item->name,
        ]);
        $purchaseStatement->amount = $sale->price;
        $purchaseStatement->total = $sale->price;
        $purchaseStatement->type = Statement::TYPE_CREDIT;
        $purchaseStatement->save();

        $buyerTax = $sale->buyer_tax;
        if ($buyerTax) {
            $amount = ($sale->price * $buyerTax->rate) / 100;
            $user->increment('balance', $amount);

            $buyerTaxStatement = new Statement();
            $buyerTaxStatement->user_id = $user->id;
            $buyerTaxStatement->title = translate('[Refund] :tax_name (:tax_rate%) Purchase #:id (:item_name)', [
                'id' => $purchase->id,
                'item_name' => $item->name,
                'tax_name' => $buyerTax->name,
                'tax_rate' => $buyerTax->rate,
            ]);
            $buyerTaxStatement->amount = $amount;
            $buyerTaxStatement->total = $amount;
            $buyerTaxStatement->type = Statement::TYPE_CREDIT;
            $buyerTaxStatement->save();
        }

        $purchase->status = Purchase::STATUS_REFUNDED;
        $purchase->update();

        $authorTax = $sale->author_tax;
        $authorEarning = $authorTax ? ($sale->author_earning + $authorTax->amount) : $sale->author_earning;

        if ($authorTax) {
            $authorTaxStatement = new Statement();
            $authorTaxStatement->user_id = $author->id;
            $authorTaxStatement->title = translate('[Refund] :tax_name (:tax_rate%) Sale #:id (:item_name)', [
                'id' => $sale->id,
                'item_name' => $item->name,
                'tax_name' => $authorTax->name,
                'tax_rate' => $authorTax->rate,
            ]);
            $authorTaxStatement->amount = $authorTax->amount;
            $authorTaxStatement->total = $authorTax->amount;
            $authorTaxStatement->type = Statement::TYPE_CREDIT;
            $authorTaxStatement->save();
        }

        $saleStatement = new Statement();
        $saleStatement->user_id = $author->id;
        $saleStatement->title = translate('[Refund] Sale #:id (:item_name)', ['id' => $sale->id, 'item_name' => $item->name]);
        $saleStatement->amount = $authorEarning;
        $saleStatement->buyer_fee = $sale->buyer_fee;
        $saleStatement->author_fee = $sale->author_fee;
        $saleStatement->total = $sale->price;
        $saleStatement->type = Statement::TYPE_DEBIT;
        $saleStatement->save();

        app(SupportHandler::class)->refund($purchase);

        if ($referralEarning) {

            $referral = $referralEarning->referral;
            $referralAuthor = $referralEarning->author;

            $referral->decrement('earnings', $referralEarning->author_earning);
            $referralAuthor->decrement('balance', $referralEarning->author_earning);
            $referralAuthor->decrement('total_referrals_earnings', $referralEarning->author_earning);

            $referralStatement = new Statement();
            $referralStatement->user_id = $referralAuthor->id;
            $referralStatement->title = translate('[Refund] Referral Earnings #:id', ['id' => $referralEarning->id]);
            $referralStatement->amount = $referralEarning->author_earning;
            $referralStatement->total = $referralEarning->author_earning;
            $referralStatement->type = Statement::TYPE_DEBIT;
            $referralStatement->save();

            $referralEarning->status = ReferralEarning::STATUS_REFUNDED;
            $referralEarning->update();
        }

        $item->decrement('total_sales');
        $item->decrement('total_sales_amount', $sale->price);
        $item->decrement('total_earnings', $authorEarning);

        $itemReview = $item->reviews->where('user_id', $user->id)->first();
        if ($itemReview) {
            $purchases = Purchase::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->active()->get();
            if ($purchases->count() < 1) {
                $itemReview->delete();
            }
        }
    }
}