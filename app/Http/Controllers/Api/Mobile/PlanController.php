<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PremiumController;
use App\Models\Plan;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * List all active subscription / premium plans.
     */
    public function index()
    {
        $plans = Plan::active()->get();

        return response()->json([
            'success' => true,
            'plans'   => $plans->map(function ($plan) {
                return [
                    'id'                => $plan->id,
                    'name'              => $plan->name,
                    'short_description' => $plan->description,
                    'interval'          => $plan->interval,
                    'interval_name'     => $plan->getIntervalName(),
                    'price'             => (float) $plan->price,
                    'is_free'           => (bool) $plan->isFree(),
                    'is_featured'       => (bool) $plan->isFeatured(),
                    'custom_features'   => $plan->custom_features ? (is_string($plan->custom_features) ? json_decode($plan->custom_features, true) : (array) $plan->custom_features) : [],
                    'downloads'         => (int) $plan->downloads,
                ];
            }),
        ], 200);
    }

    /**
     * Get authenticated user's current subscription status & quota.
     */
    public function userSubscription(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $subscription = $user->subscription;
        $isSubscribed = method_exists($user, 'isSubscribed') ? (bool) $user->isSubscribed() : false;

        $daysRemaining = null;
        if ($subscription && $subscription->expiry_at) {
            $daysRemaining = max(0, (int) Carbon::now()->diffInDays(Carbon::parse($subscription->expiry_at), false));
        }

        return response()->json([
            'success'       => true,
            'is_subscribed' => $isSubscribed,
            'subscription'  => $subscription ? [
                'id'                     => $subscription->id,
                'plan_id'                => $subscription->plan_id,
                'plan_name'              => $subscription->plan ? $subscription->plan->name : 'Premium Plan',
                'interval_name'          => $subscription->plan ? $subscription->plan->getIntervalName() : null,
                'price'                  => $subscription->plan ? (float) $subscription->plan->price : 0,
                'status'                 => $subscription->status,
                'total_downloads'        => (int) $subscription->total_downloads,
                'downloads_limit'        => $subscription->plan ? $subscription->plan->downloads : null,
                'is_unlimited'           => $subscription->plan ? (bool) $subscription->plan->hasUnlimitedDownloads() : false,
                'is_daily_limit_reached' => (bool) $subscription->isDailyLimitReached(),
                'is_expired'             => (bool) $subscription->isExpired(),
                'is_about_to_expire'     => (bool) $subscription->isAboutToExpire(),
                'days_remaining'         => $daysRemaining,
                'expires_at'             => $subscription->expiry_at ? $subscription->expiry_at->toISOString() : null,
                'created_at'             => $subscription->created_at ? $subscription->created_at->toISOString() : null,
            ] : null,
        ], 200);
    }

    /**
     * Subscribe to a free plan or initiate checkout for a paid plan.
     */
    public function subscribe(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $plan = Plan::where('id', $id)->active()->first();
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'The selected subscription plan does not exist or is inactive.',
            ], 404);
        }

        $subscription = $user->subscription;

        // Validation parity with web PremiumController
        if ($subscription) {
            if ($subscription->plan && $subscription->plan->isLifetime()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are on a lifetime plan and it cannot be renewed.',
                ], 400);
            }

            if ($subscription->plan && $subscription->plan->id == $plan->id) {
                if (!$subscription->isAboutToExpire() && !$subscription->isExpired()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are already actively subscribed to this plan.',
                    ], 400);
                }

                if ($subscription->plan->isFree() && $subscription->isExpired()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your free plan has already expired and cannot be renewed.',
                    ], 400);
                }
            } else {
                if ($plan->isFree()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not eligible for the free plan subscription.',
                    ], 400);
                }
            }
        }

        if ($plan->isFree() && $user->wasSubscribed()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for the free plan subscription.',
            ], 400);
        }

        // 1. Instant Activation for Free Plans
        if ($plan->isFree()) {
            try {
                $newSubscription = PremiumController::handleSubscription($user, $plan);
                return response()->json([
                    'success'       => true,
                    'message'       => 'Subscribed to free plan successfully.',
                    'is_subscribed' => true,
                    'subscription'  => [
                        'id'         => $newSubscription->id,
                        'plan_id'    => $plan->id,
                        'plan_name'  => $plan->name,
                        'expires_at' => $newSubscription->expiry_at ? $newSubscription->expiry_at->toISOString() : null,
                    ],
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        // 2. Paid Plan: Option to Pay with Account Balance
        if ($request->boolean('pay_with_balance')) {
            if ($user->balance < $plan->price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance to subscribe to this plan.',
                ], 400);
            }

            DB::beginTransaction();
            try {
                $user->decrement('balance', $plan->price);

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $plan->price;
                $transaction->total = $plan->price;
                $transaction->type = Transaction::TYPE_SUBSCRIPTION;
                $transaction->plan_id = $plan->id;
                $transaction->status = Transaction::STATUS_PAID;
                $transaction->save();

                $newSubscription = PremiumController::handleSubscription($user, $plan);

                $statement = new Statement();
                $statement->user_id = $user->id;
                $statement->title = '[Subscription] #' . $newSubscription->id . ' - ' . $plan->name . ' (' . $plan->getIntervalName() . ')';
                $statement->amount = $plan->price;
                $statement->total = $plan->price;
                $statement->type = Statement::TYPE_DEBIT;
                $statement->save();

                DB::commit();

                return response()->json([
                    'success'       => true,
                    'message'       => 'Subscribed successfully using your wallet balance.',
                    'is_subscribed' => true,
                    'user_balance'  => (float) $user->balance,
                    'subscription'  => [
                        'id'         => $newSubscription->id,
                        'plan_id'    => $plan->id,
                        'plan_name'  => $plan->name,
                        'expires_at' => $newSubscription->expiry_at ? $newSubscription->expiry_at->toISOString() : null,
                    ],
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        // 3. Paid Plan: Initialize Gateway Checkout Transaction
        try {
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $plan->price;
            $transaction->total = $plan->price;
            $transaction->type = Transaction::TYPE_SUBSCRIPTION;
            $transaction->plan_id = $plan->id;
            $transaction->status = Transaction::STATUS_UNPAID;
            $transaction->save();

            $checkoutUrl = function_exists('hash_encode') ? route('checkout.index', hash_encode($transaction->id)) : url('/checkout/' . $transaction->id);

            return response()->json([
                'success'              => true,
                'message'              => 'Subscription transaction initialized.',
                'transaction_id'       => $transaction->id,
                'transaction_hash'     => function_exists('hash_encode') ? hash_encode($transaction->id) : (string) $transaction->id,
                'amount'               => (float) $plan->price,
                'checkout_url'         => $checkoutUrl,
                'user_balance'         => (float) $user->balance,
                'can_pay_with_balance' => (bool) ($user->balance >= $plan->price),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

