<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\PaymentGateway;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Get active payment gateways for mobile checkout.
     */
    public function gateways(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $gateways = PaymentGateway::where('status', 1)
            ->orderBy('sort_id', 'asc')
            ->get();

        return response()->json([
            'success'      => true,
            'user_balance' => (float) $user->balance,
            'gateways'     => $gateways->map(function ($gw) {
                return [
                    'id'          => $gw->id,
                    'name'        => $gw->name,
                    'alias'       => $gw->alias,
                    'logo'        => $gw->logo ? asset($gw->logo) : null,
                    'fees'        => (float) $gw->fees,
                    'is_sandbox'  => (bool) $gw->test_mode,
                ];
            }),
        ], 200);
    }

    /**
     * Initialize mobile checkout transaction from cart.
     */
    public function createTransaction(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['item.category', 'item.discount'])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        $totalAmount = 0;
        foreach ($cartItems as $cartItem) {
            $totalAmount += $cartItem->getTotalAmount();
        }

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $totalAmount;
        $transaction->total = $totalAmount;
        $transaction->type = Transaction::TYPE_PURCHASE;
        $transaction->status = Transaction::STATUS_UNPAID;
        $transaction->save();

        foreach ($cartItems as $cartItem) {
            $item = $cartItem->item;
            $price = $cartItem->isLicenseTypeRegular() ? $item->price->regular : $item->price->extended;

            $transactionItem = new TransactionItem();
            $transactionItem->transaction_id = $transaction->id;
            $transactionItem->item_id = $item->id;
            $transactionItem->license_type = $cartItem->license_type;
            $transactionItem->price = $price;
            $transactionItem->quantity = $cartItem->quantity;
            $transactionItem->total = $cartItem->getTotalAmount();
            $transactionItem->save();
        }

        return response()->json([
            'success'        => true,
            'transaction_id' => $transaction->id,
            'total_amount'   => (float) $totalAmount,
            'currency'       => function_exists('defaultCurrency') ? @defaultCurrency()->code : 'USD',
            'user_balance'   => (float) $user->balance,
            'can_pay_balance'=> $user->balance >= $totalAmount,
        ], 201);
    }

    /**
     * Complete purchase instantly using account wallet balance.
     */
    public function payWithBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'exists:transactions,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $transaction = Transaction::where('id', $request->transaction_id)
            ->where('user_id', $user->id)
            ->where('status', Transaction::STATUS_UNPAID)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already paid.',
            ], 404);
        }

        if ($user->balance < $transaction->total) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance. Please top up or choose another payment method.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Deduct user wallet balance
            $user->decrement('balance', $transaction->total);

            // Mark transaction as paid
            $transaction->status = Transaction::STATUS_PAID;
            $transaction->payment_gateway = 'balance';
            $transaction->save();

            // Create purchases & author earnings
            foreach ($transaction->items as $trxItem) {
                $item = $trxItem->item;

                // Create purchase record
                $purchase = Purchase::create([
                    'user_id'      => $user->id,
                    'author_id'    => $item->author_id,
                    'item_id'      => $item->id,
                    'license_type' => $trxItem->license_type,
                    'code'         => strtoupper(Str::random(20)),
                    'status'       => Purchase::STATUS_ACTIVE,
                ]);

                // Credit Author Earnings
                $author = $item->author;
                if ($author) {
                    $commissionRate = 0.70; // 70% to author default
                    $authorEarning = round($trxItem->total * $commissionRate, 2);
                    $author->increment('balance', $authorEarning);
                    $author->increment('total_sales_amount', $authorEarning);
                    $author->increment('total_sales', 1);

                    // Record Sale
                    Sale::create([
                        'author_id'     => $author->id,
                        'buyer_id'      => $user->id,
                        'item_id'       => $item->id,
                        'price'         => $trxItem->total,
                        'author_earning'=> $authorEarning,
                        'license_type'  => $trxItem->license_type,
                    ]);
                }

                // Increment item total sales
                $item->increment('total_sales', 1);
            }

            // Empty cart
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Purchase completed successfully!',
                'new_balance'    => (float) $user->fresh()->balance,
                'transaction_id' => $transaction->id,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Process checkout payment via gateway (Flutterwave, PayPal, etc.)
     */
    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => ['required', 'exists:transactions,id'],
            'payment_method' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $transaction = Transaction::where('id', $request->transaction_id)
            ->where('user_id', $user->id)
            ->where('status', Transaction::STATUS_UNPAID)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already processed.',
            ], 404);
        }

        $alias = strtolower(trim($request->payment_method));
        if ($alias === 'balance') {
            return $this->payWithBalance($request);
        }

        $paymentGateway = PaymentGateway::where('alias', $alias)
            ->where('status', 1)
            ->first();

        if (!$paymentGateway) {
            $paymentGateway = PaymentGateway::where('alias', 'LIKE', $alias)->first();
        }

        if (!$paymentGateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway "' . $alias . '" is currently not active.',
            ], 400);
        }

        $transaction->payment_gateway_id = $paymentGateway->id;
        $transaction->save();

        $controllerName = ucfirst(Str::studly($paymentGateway->alias));
        $class = "App\\Http\\Controllers\\Payments\\{$controllerName}Controller";

        if (!class_exists($class)) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway processor not supported.',
            ], 500);
        }

        try {
            $processor = new $class();
            $response = json_decode($processor->process($transaction));

            if ($response && isset($response->type) && $response->type === 'success' && isset($response->redirect_url)) {
                return response()->json([
                    'success'      => true,
                    'payment_url'  => $response->redirect_url,
                    'redirect_url' => $response->redirect_url,
                ], 200);
            }

            $errorMsg = ($response && isset($response->msg)) ? $response->msg : 'Unable to initialize payment session.';
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error initializing payment gateway: ' . $e->getMessage(),
            ], 500);
        }
    }
}
