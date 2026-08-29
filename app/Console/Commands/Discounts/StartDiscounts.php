<?php

namespace App\Console\Commands\Discounts;

use App\Models\Badge;
use App\Models\Item;
use App\Models\ItemDiscount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StartDiscounts extends Command
{
    protected $signature = 'app:discounts-start';

    protected $description = 'Start Inactive discounts';

    public function handle()
    {
        $discounts = ItemDiscount::started()->inactive()->get();

        foreach ($discounts as $discount) {
            $item = $discount->item;
            $item->is_on_discount = Item::DISCOUNT_ON;
            $item->last_discount_at = Carbon::parse($discount->ending_at);
            $item->update();

            $discount->status = ItemDiscount::STATUS_ACTIVE;
            $discount->update();

            $badge = Badge::where('alias', Badge::DISCOUNT_MASTER_BADGE_ALIAS)->first();
            if ($badge) {
                $item->author->addBadge($badge);
            }
        }

        $this->info('The discounts has been started');
    }
}
