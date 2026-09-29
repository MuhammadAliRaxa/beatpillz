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
            // Check request inputs, session fallback, then cookie
            $refCode = request()->input('ref')
                ?? request()->input('referral_code')
                ?? (request()->hasSession() ? session('ref') : null)
                ?? request()->cookie('_ref');

            if ($refCode) {
                $author = User::where('username', strtolower(trim($refCode)))->first();

                // Prevent self-referral and avoid duplicate records
                if ($author && $author->id !== $user->id) {
                    $existingReferral = Referral::where('user_id', $user->id)->first();
                    if (!$existingReferral) {
                        $referral = new Referral();
                        $referral->author_id = $author->id;
                        $referral->user_id = $user->id;
                        $referral->save();

                        $badge = Badge::where('alias', Badge::REFERRER_BADGE_ALIAS)->first();
                        if ($badge) {
                            $author->addBadge($badge);
                        }
                    }

                    Cookie::queue(Cookie::forget('_ref'));
                    if (request()->hasSession()) {
                        session()->forget('ref');
                    }
                }
            }
        }
    }
}
