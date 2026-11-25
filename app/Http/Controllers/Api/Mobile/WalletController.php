<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Wallet credit amounts keyed by App Store consumable product ID.
     * Server-side only — the client NEVER sends the dollar amount.
     */
    protected const PACK_AMOUNTS = [
        'com.beatpillz.wallet.10'  => 10.00,
        'com.beatpillz.wallet.25'  => 25.00,
        'com.beatpillz.wallet.50'  => 50.00,
        'com.beatpillz.wallet.100' => 100.00,
    ];

    /**
     * POST /api/v1/user/wallet/iap-topup
     *
     * Called by the Flutter app immediately after a successful Apple IAP consumable purchase.
     * Verifies the product ID, then idempotently credits the user's wallet balance.
     *
     * Request payload:
     *   - product_id     : string  e.g. "com.beatpillz.wallet.25"
     *   - transaction_id : string  Apple StoreKit transactionID (unique per purchase)
     *   - store          : string? "app_store" (default) | "play_store"
     *   - environment    : string? "production" (default) | "sandbox"
     *
     * Response (200):
     *   { success, message, credited, new_balance, transaction_id }
     */
    public function iapTopup(Request $request)
    {
        $user = $request->user();

        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'product_id'     => 'required|string|max:100',
            'transaction_id' => 'required|string|max:255',
            'store'          => 'nullable|string|in:app_store,play_store',
            'environment'    => 'nullable|string|in:production,sandbox',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $productId     = $request->input('product_id');
        $transactionId = $request->input('transaction_id');
        $store         = $request->input('store', 'app_store');
        $environment   = $request->input('environment', 'production');

        // 2. Validate product_id against known wallet packs (server determines amount)
        if (!array_key_exists($productId, self::PACK_AMOUNTS)) {
            Log::warning('IAP Top-Up: Unknown product_id attempted.', [
                'user_id'    => $user->id,
                'product_id' => $productId,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid wallet pack product ID.',
            ], 422);
        }

        $creditAmount = self::PACK_AMOUNTS[$productId];

        // 3. Idempotency check — if this transaction_id already processed, return success
        $existingIap = DB::table('iap_transactions')
            ->where('transaction_id', $transactionId)
            ->first();

        if ($existingIap) {
            Log::info('IAP Top-Up: Duplicate transaction_id — returning cached success.', [
                'user_id'        => $user->id,
                'transaction_id' => $transactionId,
            ]);
            // Refresh balance from DB
            $user->refresh();
            return response()->json([
                'success'        => true,
                'message'        => 'Wallet already credited for this transaction.',
                'credited'       => $creditAmount,
                'new_balance'    => (float) $user->balance,
                'transaction_id' => $transactionId,
            ], 200);
        }

        // 4. Credit wallet atomically
        DB::beginTransaction();
        try {
            // 4a. Record IAP transaction (unique constraint prevents double-credit)
            DB::table('iap_transactions')->insert([
                'user_id'        => $user->id,
                'store'          => $store,
                'product_id'     => $productId,
                'transaction_id' => $transactionId,
                'amount'         => $creditAmount,
                'environment'    => $environment,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 4b. Increment user balance
            $user->increment('balance', $creditAmount);

            // 4c. Create Transaction record (for revenue accounting)
            $transaction             = new Transaction();
            $transaction->user_id   = $user->id;
            $transaction->amount    = $creditAmount;
            $transaction->total     = $creditAmount;
            $transaction->type      = Transaction::TYPE_DEPOSIT;
            $transaction->status    = Transaction::STATUS_PAID;
            $transaction->save();

            // 4d. Create Statement entry (shows in user's wallet history)
            $statement          = new Statement();
            $statement->user_id = $user->id;
            $statement->title   = "[Apple IAP Top-Up] {$productId}";
            $statement->amount  = $creditAmount;
            $statement->total   = $creditAmount;
            $statement->type    = Statement::TYPE_CREDIT;
            $statement->save();

            DB::commit();

            // Refresh to get updated balance
            $user->refresh();

            Log::info('IAP Top-Up: Wallet credited successfully.', [
                'user_id'        => $user->id,
                'product_id'     => $productId,
                'credited'       => $creditAmount,
                'new_balance'    => $user->balance,
                'transaction_id' => $transactionId,
            ]);

            return response()->json([
                'success'        => true,
                'message'        => 'Wallet credited successfully.',
                'credited'       => $creditAmount,
                'new_balance'    => (float) $user->balance,
                'transaction_id' => $transactionId,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('IAP Top-Up: Failed to credit wallet.', [
                'user_id'        => $user->id,
                'transaction_id' => $transactionId,
                'error'          => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to credit wallet. Please try again.',
            ], 500);
        }
    }
}
