<?php

namespace App\Jobs\Author;

use App\Models\Item;
use App\Models\PremiumEarning;
use App\Models\Statement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleAuthorPremiumEarnings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $transaction;

    public $subscription;

    public function __construct($transaction, $subscription)
    {
        $this->transaction = $transaction;
        $this->subscription = $subscription;
    }

    public function handle()
    {
        $transaction = $this->transaction;
        $plan = $transaction->plan;

        if ($plan && $plan->author_earning_percentage > 0) {
            $items = Item::whereNot('author_id', $transaction->user->id)->premium()->approved()->get();

            foreach ($items as $item) {

                $author = $item->author;

                $authorEarning = ($transaction->amount * $plan->author_earning_percentage) / 100;

                if ($authorEarning > 0.01) {
                    $premiumEarning = new PremiumEarning();
                    $premiumEarning->author_id = $author->id;
                    $premiumEarning->subscription_id = $this->subscription->id;
                    $premiumEarning->item_id = $item->id;
                    $premiumEarning->name = translate(':plan_name (:plan_interval)', [
                        'plan_name' => $plan->name,
                        'plan_interval' => $plan->getIntervalName(),
                    ]);
                    $premiumEarning->percentage = $plan->author_earning_percentage;
                    $premiumEarning->price = $transaction->amount;
                    $premiumEarning->author_earning = round($authorEarning, 2);
                    $premiumEarning->save();

                    $author->increment('balance', $premiumEarning->author_earning);

                    $statement1 = new Statement();
                    $statement1->user_id = $author->id;
                    $statement1->title = translate('[Premium Earnings] #:id (:item_name)', [
                        'id' => $premiumEarning->id,
                        'item_name' => $item->name,
                    ]);
                    $statement1->amount = $premiumEarning->author_earning;
                    $statement1->total = $premiumEarning->author_earning;
                    $statement1->type = Statement::TYPE_CREDIT;
                    $statement1->save();
                }
            }
        }
    }
}