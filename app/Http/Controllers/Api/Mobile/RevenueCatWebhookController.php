<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Plan;
use App\Models\Statement;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevenueCatWebhookController extends Controller
{
    /**
     * Handle incoming server-to-server webhooks from RevenueCat.
     * Supported stores: Apple App Store (StoreKit 2) & Google Play Billing.
     */
    public function handle(Request $request)
    {
        // 1. Verify Webhook Secret if configured in .env / config
        $expectedSecret = config('services.revenuecat.webhook_secret');
        if (!empty($expectedSecret)) {
            $authHeader = $request->header('Authorization') 
                ?? $request->header('X-RevenueCat-Secret') 
                ?? $request->query('secret');

            // Strip "Bearer " prefix if provided
            $cleanAuth = preg_replace('/^Bearer\s+/i', '', trim((string) $authHeader));

            if ($cleanAuth !== trim($expectedSecret)) {
                Log::warning('RevenueCat Webhook: Unauthorized attempt with invalid secret.', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
            }
        }

        $payload = $request->all();
        $event = $payload['event'] ?? $payload;

        $type = $event['type'] ?? 'UNKNOWN';
        $appUserId = $event['app_user_id'] ?? $event['original_app_user_id'] ?? null;
        $productId = $event['product_id'] ?? null;

        Log::info("RevenueCat Webhook received: [{$type}]", [
            'app_user_id' => $appUserId,
            'product_id'  => $productId,
            'store'       => $event['store'] ?? 'APP_STORE',
            'environment' => $event['environment'] ?? 'PRODUCTION',
        ]);

        // 2. Handle Test Webhook
        if ($type === 'TEST') {
            return response()->json([
                'success' => true,
                'message' => 'RevenueCat test webhook verified successfully.',
            ], 200);
        }

        // 3. User Resolution
        if (empty($appUserId)) {
            Log::warning('RevenueCat Webhook: Missing app_user_id in payload.');
            return response()->json(['success' => false, 'message' => 'Missing app_user_id.'], 200);
        }

        $user = User::find($appUserId);
        if (!$user) {
            $user = User::where('email', $appUserId)->orWhere('username', $appUserId)->first();
        }

        if (!$user) {
            Log::warning("RevenueCat Webhook: User not found for app_user_id '{$appUserId}'.");
            return response()->json(['success' => false, 'message' => "User '{$appUserId}' not found."], 200);
        }

        // 4. Dispatch Event Types
        switch ($type) {
            case 'INITIAL_PURCHASE':
            case 'RENEWAL':
            case 'UNCANCELLATION':
            case 'PRODUCT_CHANGE':
                return $this->processPurchaseOrRenewal($user, $event, $type);

            case 'CANCELLATION':
                return $this->processCancellation($user, $event);

            case 'EXPIRATION':
                return $this->processExpiration($user, $event);

            default:
                Log::info("RevenueCat Webhook: Unhandled event type [{$type}] acknowledged.");
                return response()->json([
                    'success' => true,
                    'message' => "Event [{$type}] acknowledged without changes.",
                ], 200);
        }
    }

    /**
     * Process initial subscription purchase, auto-renewal, or plan upgrade.
     */
    protected function processPurchaseOrRenewal(User $user, array $event, string $type)
    {
        $productId = $event['product_id'] ?? null;
        $plan = Plan::findByStoreProductId($productId);

        if (!$plan) {
            Log::warning("RevenueCat Webhook: No plan matching store product ID '{$productId}'.", [
                'user_id'    => $user->id,
                'product_id' => $productId,
            ]);
            return response()->json([
                'success' => false,
                'message' => "No plan matching product '{$productId}'.",
            ], 200);
        }

        // Calculate Expiration Date
        $expiryDate = null;
        if (!empty($event['expiration_at_ms'])) {
            $expiryDate = Carbon::createFromTimestampMs((int) $event['expiration_at_ms']);
        } elseif (!$plan->isLifetime()) {
            $existing = $user->subscription;
            if ($existing && $existing->expiry_at && !$existing->isExpired()) {
                $expiryDate = Carbon::parse($existing->expiry_at)->addDays($plan->getIntervalDays());
            } else {
                $expiryDate = Carbon::now()->addDays($plan->getIntervalDays());
            }
        }

        DB::beginTransaction();
        try {
            $subscription = $user->subscription;
            $isNew = !$subscription || $type === 'INITIAL_PURCHASE';

            // 1. Update or Create Subscription in MySQL
            $subscription = $user->subscription()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id'              => $plan->id,
                    'total_downloads'      => $isNew ? 0 : ($subscription->total_downloads ?? 0),
                    'expiry_at'            => $expiryDate,
                    'last_notification_at' => null,
                ]
            );

            // 2. Mark User Was Subscribed Flag
            $user->was_subscribed = User::WAS_SUBSCRIBED;
            $user->save();

            // 3. Award Premium Membership Badge
            $badge = Badge::where('alias', Badge::PREMIUM_MEMBERSHIP_ALIAS)->first();
            if ($badge) {
                $user->addBadge($badge);
            }

            // 4. Record Transaction for Revenue & Accounting
            $price = isset($event['price']) ? (float) $event['price'] : (float) $plan->price;
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $price;
            $transaction->total = $price;
            $transaction->type = Transaction::TYPE_SUBSCRIPTION;
            $transaction->plan_id = $plan->id;
            $transaction->status = Transaction::STATUS_PAID;
            $transaction->save();

            // 5. Generate Statement Entry for User Dashboard
            $prefix = ($type === 'RENEWAL') ? '[IAP Renewal]' : '[IAP Subscription]';
            $statement = new Statement();
            $statement->user_id = $user->id;
            $statement->title = "{$prefix} #{$subscription->id} - {$plan->name} ({$plan->getIntervalName()})";
            $statement->amount = $price;
            $statement->total = $price;
            $statement->type = Statement::TYPE_DEBIT;
            $statement->save();

            DB::commit();

            Log::info("RevenueCat Webhook: Successfully processed [{$type}] for user #{$user->id} -> Plan #{$plan->id} ({$plan->name}).", [
                'expiry_at' => $expiryDate ? $expiryDate->toDateTimeString() : 'LIFETIME',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Subscription {$type} processed successfully.",
                'subscription_id' => $subscription->id,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('RevenueCat Webhook: Database error processing purchase: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process subscription cancellation (auto-renew turned off).
     * Note: In StoreKit, user retains paid access until expiration_at_ms.
     */
    protected function processCancellation(User $user, array $event)
    {
        $subscription = $user->subscription;
        if ($subscription) {
            // If Apple immediately revoked access (refund), update expiry to past
            if (!empty($event['expiration_at_ms']) && Carbon::createFromTimestampMs((int) $event['expiration_at_ms'])->isPast()) {
                $subscription->update([
                    'expiry_at' => Carbon::now()->subMinute(),
                ]);
            }
        }

        Log::info("RevenueCat Webhook: Cancellation recorded for user #{$user->id}.");
        return response()->json(['success' => true, 'message' => 'Cancellation recorded.'], 200);
    }

    /**
     * Process subscription expiration or revocation.
     */
    protected function processExpiration(User $user, array $event)
    {
        $subscription = $user->subscription;
        if ($subscription) {
            $subscription->update([
                'expiry_at' => Carbon::now()->subMinute(),
            ]);
        }

        Log::info("RevenueCat Webhook: Expiration recorded for user #{$user->id}.");
        return response()->json(['success' => true, 'message' => 'Subscription marked as expired.'], 200);
    }
}
