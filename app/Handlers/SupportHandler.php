<?php

namespace App\Handlers;

use App\Models\AuthorTax;
use App\Models\Statement;
use App\Models\SupportEarning;
use Carbon\Carbon;

class SupportHandler
{
    public function create($purchase, $trx, $support = null)
    {
        if (@settings('item')->support_status && $support) {

            $item = $purchase->item;
            $author = $purchase->author;
            $user = $purchase->user;
            $supportPrice = $support->price;

            $expiryDate = Carbon::now()->addDays($support->days);

            if ($supportPrice > 0) {

                $authorFeesPercentage = $author->level->fees;
                $authorFeesAmount = $authorFeesPercentage > 0 ? ($supportPrice * $authorFeesPercentage) / 100 : 0;
                $authorEarningAmount = $authorFeesAmount > 0 ? ($supportPrice - $authorFeesAmount) : $supportPrice;

                $authorTax = $this->authorTaxCalculate($author, $authorEarningAmount, @$user->address->country);

                $buyerTax = $this->buyerTaxCalculate($trx, $supportPrice);

                $authorEarning = $authorTax ? ($authorEarningAmount - $authorTax['amount']) : $authorEarningAmount;

                $supportEarning = new SupportEarning();
                $supportEarning->author_id = $author->id;
                $supportEarning->purchase_id = $purchase->id;
                $supportEarning->name = $support->name;
                $supportEarning->title = $support->title;
                $supportEarning->days = $support->days;
                $supportEarning->price = $supportPrice;
                $supportEarning->buyer_tax = $buyerTax;
                $supportEarning->author_fee = $authorFeesAmount;
                $supportEarning->author_tax = $authorTax;
                $supportEarning->author_earning = $authorEarning;
                $supportEarning->support_expiry_at = $expiryDate;
                $supportEarning->save();

                $author->increment('balance', $supportEarning->author_earning);

                $this->createBuyerNewSupportStatements($user, $trx, $item, $supportEarning);

                $this->createAuthorNewSupportStatements($author, $item, $supportEarning);
            }

            if ($purchase->support_expiry_at) {
                if ($purchase->isSupportExpired()) {
                    $purchase->support_expiry_at = $expiryDate;
                }
            } else {
                $purchase->support_expiry_at = $expiryDate;
            }

            $purchase->update();
        }
    }

    public function refund($purchase)
    {
        if ($purchase->support_expiry_at && !$purchase->isSupportExpired()) {
            $supportEarning = $purchase->supportEarnings->where('support_expiry_at', $purchase->support_expiry_at)->first();

            if ($supportEarning) {
                $item = $purchase->item;
                $user = $purchase->user;
                $author = $supportEarning->author;

                $author->decrement('balance', $supportEarning->author_earning);

                $statement1 = new Statement();
                $statement1->user_id = $author->id;
                $statement1->title = translate('[Refund] Support Earnings #:id (:item_name)', [
                    'id' => $supportEarning->id,
                    'item_name' => $item->name,
                ]);
                $statement1->amount = $supportEarning->author_earning;
                $statement1->author_fee = $supportEarning->author_fee;
                $statement1->total = $supportEarning->price;
                $statement1->type = Statement::TYPE_DEBIT;
                $statement1->save();

                if ($supportEarning->author_tax) {
                    $statement2 = new Statement();
                    $statement2->user_id = $author->id;
                    $statement2->title = translate('[Refund] :tax_name (:tax_rate%) Support Earnings #:id (:item_name)', [
                        'id' => $supportEarning->id,
                        'item_name' => $item->name,
                        'tax_name' => $supportEarning->author_tax->name,
                        'tax_rate' => $supportEarning->author_tax->rate,
                    ]);
                    $statement2->amount = $supportEarning->author_tax->amount;
                    $statement2->total = $supportEarning->author_tax->amount;
                    $statement2->type = Statement::TYPE_DEBIT;
                    $statement2->save();
                }

                $user->increment('balance', $supportEarning->price);

                $statement3 = new Statement();
                $statement3->user_id = $user->id;
                $statement3->title = translate('[Refund] Support #:id (:item_name)', [
                    'id' => $supportEarning->id,
                    'item_name' => $item->name,
                ]);
                $statement3->amount = $supportEarning->price;
                $statement3->total = $supportEarning->price;
                $statement3->type = Statement::TYPE_CREDIT;
                $statement3->save();

                if ($supportEarning->buyer_tax) {
                    $user->increment('balance', $supportEarning->buyer_tax->amount);
                    $statement4 = new Statement();
                    $statement4->user_id = $user->id;
                    $statement4->title = translate('[Refund] :tax_name (:tax_rate%) Support #:id (:item_name)', [
                        'id' => $supportEarning->id,
                        'item_name' => $item->name,
                        'tax_name' => $supportEarning->buyer_tax->name,
                        'tax_rate' => $supportEarning->buyer_tax->rate,
                    ]);
                    $statement4->amount = $supportEarning->buyer_tax->amount;
                    $statement4->total = $supportEarning->buyer_tax->amount;
                    $statement4->type = Statement::TYPE_CREDIT;
                    $statement4->save();
                }

                $supportEarning->status = SupportEarning::STATUS_REFUNDED;
                $supportEarning->update();
            }
        }
    }

    public function cancel($purchase)
    {
        if ($purchase->support_expiry_at && !$purchase->isSupportExpired()) {
            $supportEarning = $purchase->supportEarnings->where('support_expiry_at', $purchase->support_expiry_at)->first();

            if ($supportEarning) {
                $item = $purchase->item;
                $user = $purchase->user;
                $author = $supportEarning->author;

                $author->decrement('balance', $supportEarning->author_earning);

                $statement1 = new Statement();
                $statement1->user_id = $author->id;
                $statement1->title = translate('[Cancellation] Support Earnings #:id (:item_name)', [
                    'id' => $supportEarning->id,
                    'item_name' => $item->name,
                ]);
                $statement1->amount = $supportEarning->author_earning;
                $statement1->author_fee = $supportEarning->author_fee;
                $statement1->total = $supportEarning->price;
                $statement1->type = Statement::TYPE_DEBIT;
                $statement1->save();

                if ($supportEarning->author_tax) {
                    $statement2 = new Statement();
                    $statement2->user_id = $author->id;
                    $statement2->title = translate('[Cancellation] :tax_name (:tax_rate%) Support Earnings #:id (:item_name)', [
                        'id' => $supportEarning->id,
                        'item_name' => $item->name,
                        'tax_name' => $supportEarning->author_tax->name,
                        'tax_rate' => $supportEarning->author_tax->rate,
                    ]);
                    $statement2->amount = $supportEarning->author_tax->amount;
                    $statement2->total = $supportEarning->author_tax->amount;
                    $statement2->type = Statement::TYPE_DEBIT;
                    $statement2->save();
                }

                $user->increment('balance', $supportEarning->price);

                $statement3 = new Statement();
                $statement3->user_id = $user->id;
                $statement3->title = translate('[Cancellation] Support #:id (:item_name)', [
                    'id' => $supportEarning->id,
                    'item_name' => $item->name,
                ]);
                $statement3->amount = $supportEarning->price;
                $statement3->total = $supportEarning->price;
                $statement3->type = Statement::TYPE_CREDIT;
                $statement3->save();

                if ($supportEarning->buyer_tax) {
                    $user->increment('balance', $supportEarning->buyer_tax->amount);
                    $statement4 = new Statement();
                    $statement4->user_id = $user->id;
                    $statement4->title = translate('[Cancellation] :tax_name (:tax_rate%) Support #:id (:item_name)', [
                        'id' => $supportEarning->id,
                        'item_name' => $item->name,
                        'tax_name' => $supportEarning->buyer_tax->name,
                        'tax_rate' => $supportEarning->buyer_tax->rate,
                    ]);
                    $statement4->amount = $supportEarning->buyer_tax->amount;
                    $statement4->total = $supportEarning->buyer_tax->amount;
                    $statement4->type = Statement::TYPE_CREDIT;
                    $statement4->save();
                }

                $supportEarning->status = SupportEarning::STATUS_CANCELLED;
                $supportEarning->update();
            }
        }
    }

    private function buyerTaxCalculate($trx, $supportPrice)
    {
        $buyer_tax = null;
        if ($trx->tax) {
            $buyerTaxAmount = ($supportPrice * $trx->tax->rate) / 100;
            $buyer_tax = [
                'name' => $trx->tax->name,
                'rate' => $trx->tax->rate,
                'amount' => round($buyerTaxAmount, 2),
            ];
        }

        return $buyer_tax;
    }

    private function authorTaxCalculate($author, $authorEarningAmount, $country)
    {
        $author_tax = null;
        $authorTax = AuthorTax::whereJsonContains('countries', $country)->first();
        if ($authorTax) {
            $authorTaxAmount = ($authorEarningAmount * $authorTax->rate) / 100;
            $authorEarningAmount = ($authorEarningAmount - $authorTaxAmount);
            $author_tax = [
                'name' => $authorTax->name,
                'rate' => $authorTax->rate,
                'amount' => round($authorTaxAmount, 2),
            ];
        }

        return $author_tax;
    }

    private function createBuyerNewSupportStatements($user, $trx, $item, $supportEarning)
    {
        $type = translate('Support Purchase');
        if ($trx->isTypeSupportExtend()) {
            $type = translate('Support Extend');
        }

        $statement1 = new Statement();
        $statement1->user_id = $user->id;
        $statement1->title = translate('[:type] #:id (:item_name)', [
            'type' => $type,
            'id' => $supportEarning->id,
            'item_name' => $item->name,
        ]);
        $statement1->amount = $supportEarning->price;
        $statement1->total = $supportEarning->price;
        $statement1->type = Statement::TYPE_DEBIT;
        $statement1->save();

        if ($supportEarning->buyer_tax) {
            $statement2 = new Statement();
            $statement2->user_id = $user->id;
            $statement2->title = translate('[:tax_name (:tax_rate%)] :type #:id (:item_name)', [
                'id' => $supportEarning->id,
                'item_name' => $item->name,
                'tax_name' => $supportEarning->buyer_tax->name,
                'tax_rate' => $supportEarning->buyer_tax->rate,
                'type' => $type,
            ]);
            $statement2->amount = $supportEarning->buyer_tax->amount;
            $statement2->total = $supportEarning->buyer_tax->amount;
            $statement2->type = Statement::TYPE_DEBIT;
            $statement2->save();
        }
    }

    private function createAuthorNewSupportStatements($author, $item, $supportEarning)
    {
        $statement3 = new Statement();
        $statement3->user_id = $author->id;
        $statement3->title = translate('[Support Earnings] #:id (:item_name)', [
            'id' => $supportEarning->id,
            'item_name' => $item->name,
        ]);
        $statement3->amount = $supportEarning->price;
        $statement3->author_fee = $supportEarning->author_fee;
        $statement3->total = ($supportEarning->price - $supportEarning->author_fee);
        $statement3->type = Statement::TYPE_CREDIT;
        $statement3->save();

        if ($supportEarning->author_tax) {
            $statement4 = new Statement();
            $statement4->user_id = $author->id;
            $statement4->title = translate('[:tax_name (:tax_rate%)] Support Earnings #:id (:item_name)', [
                'id' => $supportEarning->id,
                'item_name' => $item->name,
                'tax_name' => $supportEarning->author_tax->name,
                'tax_rate' => $supportEarning->author_tax->rate,
            ]);
            $statement4->amount = $supportEarning->author_tax->amount;
            $statement4->total = $supportEarning->author_tax->amount;
            $statement4->type = Statement::TYPE_DEBIT;
            $statement4->save();
        }
    }
}