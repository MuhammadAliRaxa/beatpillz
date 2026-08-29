<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateAuthorLevel extends Command
{
    protected $signature = 'app:update-author-level';

    protected $description = 'This command is for updating the author level';

    public function handle()
    {
        $authors = User::author()->get();

        foreach ($authors as $author) {
            $totalSalesEarnings = $author->total_sales_amount;

            $level = Level::where('min_earnings', '<=', $totalSalesEarnings)
                ->orderBy('min_earnings', 'desc')
                ->with('badge')
                ->first();

            if ($level) {
                $author->level_id = $level->id;
                $author->save();
            }

            if ($level->badge) {
                $author->addBadge($level->badge);
            }
        }

        $this->info('Author levels updated successfully');
    }
}
