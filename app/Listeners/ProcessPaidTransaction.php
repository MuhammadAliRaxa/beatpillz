<?php

namespace App\Listeners;

use App\Events\SaleCreated;
use App\Events\TransactionPaid;
use App\Handlers\SupportHandler;
use App\Http\Controllers\PremiumController;
use App\Jobs\Author\HandleAuthorPremiumEarnings;
use App\Jobs\SendPaymentConfirmationNotification;
use App\Models\AuthorTax;
use App\Models\Sale;
use App\Models\Statement;
use Illuminate\Support\Str;

class ProcessPaidTransaction
{
    public function handle(TransactionPaid $event)
    {
        $trx = $event->transaction;

        try {
            if ($trx->isPaid()) {
                dispatch(new SendPaymentConfirmationNotification($trx));
                $method = 'handle' . Str::studly($trx->type) . 'Transaction';
                $this->{$method}($trx);
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    private function handlePurchaseTransaction($trx)
    {
        $trxItems = $trx->trxItems;
        $user = $trx->user;
        $user_country = @$user->address->country;

        foreach ($trxItems as $trxItem) {
            $item = $trxItem->item;
            $author = $item->author;

            $buyerFee = $trxItem->isLicenseTypeRegular() ? $item->category->regular_buyer_fee : $item->category->extended_buyer_fee;

            $amountWithoutBuyerFee = $buyerFee > 0 ? ($trxItem->price - $buyerFee) : $trxItem->price;
            $authorFeesPercentage = $author->level->fees;
            $authorFeesAmount = $authorFeesPercentage > 0 ? ($amountWithoutBuyerFee * $authorFeesPercentage) / 100 : 0;
            $authorEarningAmount = $authorFeesAmount > 0 ? ($amountWithoutBuyerFee - $authorFeesAmount) : $amountWithoutBuyerFee;

            $author_tax = null;
            $authorTax = AuthorTax::whereJsonContains('countries', $user_country)->first();
            if ($authorTax) {
                $authorTaxAmount = ($authorEarningAmount * $authorTax->rate) / 100;
                $authorEarningAmount = ($authorEarningAmount - $authorTaxAmount);
                $author_tax = [
                    'name' => $authorTax->name,
                    'rate' => $authorTax->rate,
                    'amount' => round($authorTaxAmount, 2),
                ];
            }

            for ($i = 0; $i < $trxItem->quantity; $i++) {
                $sale = new Sale();
                $sale->author_id = $author->id;
                $sale->user_id = $user->id;
                $sale->item_id = $item->id;
                $sale->license_type = $trxItem->license_type;
                $sale->price = $trxItem->price;
                $sale->buyer_fee = $buyerFee;
                if ($trx->hasTax()) {
                    $buyerTaxAmount = ($trxItem->price * $trx->tax->rate) / 100;
                    $sale->buyer_tax = [
                        'name' => $trx->tax->name,
                        'rate' => $trx->tax->rate,
                        'amount' => round($buyerTaxAmount, 2),
                    ];
                }
                $sale->author_fee = $authorFeesAmount;
                $sale->author_tax = $author_tax;
                $sale->author_earning = $authorEarningAmount;
                $sale->country = $user_country ?? null;
                $sale->save();
                event(new SaleCreated($sale, $trx, $trxItem->support));
            }
        }
    }

    private function handleSupportPurchaseTransaction($trx)
    {
        app(SupportHandler::class)->create($trx->purchase, $trx, $trx->support);
    }

    private function handleSupportExtendTransaction($trx)
    {
        app(SupportHandler::class)->create($trx->purchase, $trx, $trx->support);
    }

    private function handleDepositTransaction($trx)
    {
        $user = $trx->user;

        $user->increment('balance', $trx->amount);

        $statement1 = new Statement();
        $statement1->user_id = $user->id;
        $statement1->title = translate('[Deposit] Deposit to account balance #:id', [
            'id' => $trx->id,
        ]);
        $statement1->amount = $trx->amount;
        $statement1->total = $trx->amount;
        $statement1->type = Statement::TYPE_CREDIT;
        $statement1->save();
    }

    private function handleSubscriptionTransaction($trx)
    {
        $user = $trx->user;
        $plan = $trx->plan;

        $subscription = PremiumController::handleSubscription($user, $plan);

        if ($subscription) {
            $statement1 = new Statement();
            $statement1->user_id = $user->id;
            $statement1->title = translate('[Subscription] #:id - :plan_name (:plan_interval)', [
                'id' => $subscription->id,
                'plan_name' => $plan->name,
                'plan_interval' => $plan->getIntervalName(),
            ]);
            $statement1->amount = $trx->amount;
            $statement1->total = $trx->amount;
            $statement1->type = Statement::TYPE_DEBIT;
            $statement1->save();

            if ($trx->tax) {
                $tax = $trx->tax;
                $statement2 = new Statement();
                $statement2->user_id = $user->id;
                $statement2->title = translate('[:tax_name (:tax_rate%)] Subscription #:id - :plan_name (:plan_interval)', [
                    'id' => $subscription->id,
                    'plan_name' => $plan->name,
                    'plan_interval' => $plan->getIntervalName(),
                    'tax_name' => $tax->name,
                    'tax_rate' => $tax->rate,
                ]);
                $statement2->amount = $tax->amount;
                $statement2->total = $tax->amount;
                $statement2->type = Statement::TYPE_DEBIT;
                $statement2->save();
            }

            dispatch(new HandleAuthorPremiumEarnings($trx, $subscription));
        }
    }
}