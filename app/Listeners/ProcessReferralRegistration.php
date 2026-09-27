<?php

namespace App\Listeners;

use App\Events\Registered;
use App\Models\Badge;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class ProcessReferralRegistration
{
    public function handle(Registered $event)
    {
        $user = $event->user;

        if (@settings('referral')->status) {
            if (request()->hasCookie('_ref')) {
                $author = User::where('username', request()->cookie('_ref'))->first();
                if ($author) {
                    $referral = new Referral();
                    $referral->author_id = $author->id;
                    $referral->user_id = $user->id;
                    $referral->save();

                    Cookie::queue(Cookie::forget('_ref'));

                    $badge = Badge::where('alias', Badge::REFERRER_BADGE_ALIAS)->first();
                    if ($badge) {
                        $author->addBadge($badge);
                    }
                }
            }
        }
    }
}
