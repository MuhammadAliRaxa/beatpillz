<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

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
     * Get authenticated user's current subscription status.
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

        return response()->json([
            'success'       => true,
            'is_subscribed' => method_exists($user, 'isSubscribed') ? (bool) $user->isSubscribed() : false,
            'subscription'  => $subscription ? [
                'id'         => $subscription->id,
                'plan_name'  => $subscription->plan ? $subscription->plan->name : 'Premium Plan',
                'status'     => $subscription->status,
                'expires_at' => $subscription->expiry_at ? $subscription->expiry_at->toISOString() : null,
                'created_at' => $subscription->created_at ? $subscription->created_at->toISOString() : null,
            ] : null,
        ], 200);
    }
}
