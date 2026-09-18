<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PremiumController;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\PaymentGateway;
use App\Models\Plan;
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

    /**
     * Get checkout screen data for a subscription plan (Plan info, Billing address, Gateways with calculated fees).
     */
    public function subscriptionCheckoutInfo(Request $request, $plan_id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $plan = Plan::where('id', $plan_id)->active()->first();
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Subscription plan not found.'], 404);
        }

        $gateways = PaymentGateway::where('status', 1)->orderBy('sort_id', 'asc')->get();
        $subtotal = (float) $plan->price;

        return response()->json([
            'success'         => true,
            'plan'            => [
                'id'            => $plan->id,
                'name'          => $plan->name,
                'interval'      => $plan->interval,
                'interval_name' => $plan->getIntervalName(),
                'price'         => (float) $plan->price,
                'is_free'       => (bool) $plan->isFree(),
            ],
            'user_balance'    => (float) $user->balance,
            'billing_address' => [
                'firstname'      => $user->firstname,
                'lastname'       => $user->lastname,
                'address_line_1' => $user->address_line_1,
                'address_line_2' => $user->address_line_2,
                'city'           => $user->city,
                'state'          => $user->state,
                'zip'            => $user->zip,
                'country'        => $user->country ?? 'Pakistan',
            ],
            'gateways'        => $gateways->map(function ($gw) use ($subtotal) {
                $feePct = (float) $gw->fees;
                $feeAmount = round(($subtotal * $feePct) / 100, 2);
                $total = round($subtotal + $feeAmount, 2);

                return [
                    'id'             => $gw->id,
                    'name'           => $gw->name,
                    'alias'          => $gw->alias,
                    'logo'           => $gw->logo ? asset($gw->logo) : null,
                    'fee_percentage' => $feePct,
                    'fee_amount'     => $feeAmount,
                    'subtotal'       => $subtotal,
                    'total'          => $total,
                ];
            }),
        ], 200);
    }

    /**
     * Submit subscription checkout with billing address and payment method.
     */
    public function subscriptionCheckout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'plan_id'                        => ['required', 'exists:plans,id'],
            'payment_method'                 => ['required', 'string'],
            'billing_address'                => ['nullable', 'array'],
            'billing_address.firstname'      => ['nullable', 'string', 'max:50'],
            'billing_address.lastname'       => ['nullable', 'string', 'max:50'],
            'billing_address.address_line_1' => ['nullable', 'string', 'max:255'],
            'billing_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'billing_address.city'           => ['nullable', 'string', 'max:100'],
            'billing_address.state'          => ['nullable', 'string', 'max:100'],
            'billing_address.zip'            => ['nullable', 'string', 'max:50'],
            'billing_address.country'        => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $plan = Plan::where('id', $request->plan_id)->active()->firstOrFail();

        // 1. Save / Update User Billing Address if provided
        if ($request->filled('billing_address')) {
            $addr = $request->input('billing_address');
            if (!empty($addr['firstname'])) $user->firstname = $addr['firstname'];
            if (!empty($addr['lastname'])) $user->lastname = $addr['lastname'];
            if (isset($addr['address_line_1'])) $user->address_line_1 = $addr['address_line_1'];
            if (isset($addr['address_line_2'])) $user->address_line_2 = $addr['address_line_2'];
            if (isset($addr['city'])) $user->city = $addr['city'];
            if (isset($addr['state'])) $user->state = $addr['state'];
            if (isset($addr['zip'])) $user->zip = $addr['zip'];
            if (!empty($addr['country'])) $user->country = $addr['country'];
            $user->save();
        }

        $subtotal = (float) $plan->price;
        $paymentMethod = strtolower(trim($request->payment_method));

        // 2. Pay with Account Balance
        if ($paymentMethod === 'balance') {
            if ($user->balance < $subtotal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. Please choose another payment method.',
                ], 400);
            }

            DB::beginTransaction();
            try {
                $user->decrement('balance', $subtotal);

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $subtotal;
                $transaction->fees = 0;
                $transaction->total = $subtotal;
                $transaction->type = Transaction::TYPE_SUBSCRIPTION;
                $transaction->plan_id = $plan->id;
                $transaction->status = Transaction::STATUS_PAID;
                $transaction->payment_gateway = 'balance';
                $transaction->save();

                $newSubscription = PremiumController::handleSubscription($user, $plan);

                $statement = new Statement();
                $statement->user_id = $user->id;
                $statement->title = '[Subscription] #' . $newSubscription->id . ' - ' . $plan->name . ' (' . $plan->getIntervalName() . ')';
                $statement->amount = $subtotal;
                $statement->total = $subtotal;
                $statement->type = Statement::TYPE_DEBIT;
                $statement->save();

                DB::commit();

                return response()->json([
                    'success'        => true,
                    'is_paid'        => true,
                    'message'        => 'Subscribed successfully using your wallet balance.',
                    'user_balance'   => (float) $user->fresh()->balance,
                    'subscription'   => [
                        'id'         => $newSubscription->id,
                        'plan_id'    => $plan->id,
                        'plan_name'  => $plan->name,
                        'expires_at' => $newSubscription->expiry_at ? $newSubscription->expiry_at->toISOString() : null,
                    ],
                    'order_summary'  => [
                        'item_title'     => 'Subscription - ' . $plan->name . ' (' . $plan->getIntervalName() . ')',
                        'subtotal'       => $subtotal,
                        'fee_percentage' => 0.0,
                        'fee_amount'     => 0.0,
                        'total'          => $subtotal,
                    ],
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        // 3. Pay via Gateway (Paystack, Flutterwave, Stripe, PayPal, etc.)
        $gateway = PaymentGateway::where('alias', $paymentMethod)->where('status', 1)->first();
        if (!$gateway) {
            $gateway = PaymentGateway::where('alias', 'LIKE', $paymentMethod)->where('status', 1)->first();
        }

        $feePct = $gateway ? (float) $gateway->fees : 0.0;
        $feeAmount = round(($subtotal * $feePct) / 100, 2);
        $total = round($subtotal + $feeAmount, 2);

        try {
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $subtotal;
            $transaction->fees = $feeAmount;
            $transaction->total = $total;
            $transaction->type = Transaction::TYPE_SUBSCRIPTION;
            $transaction->plan_id = $plan->id;
            $transaction->payment_gateway_id = $gateway ? $gateway->id : null;
            $transaction->status = Transaction::STATUS_UNPAID;
            $transaction->save();

            $checkoutUrl = function_exists('hash_encode') ? route('checkout.index', hash_encode($transaction->id)) : url('/checkout/' . $transaction->id);

            return response()->json([
                'success'        => true,
                'is_paid'        => false,
                'transaction_id' => $transaction->id,
                'payment_method' => $gateway ? $gateway->alias : $paymentMethod,
                'checkout_url'   => $checkoutUrl,
                'order_summary'  => [
                    'item_title'     => 'Subscription - ' . $plan->name . ' (' . $plan->getIntervalName() . ')',
                    'subtotal'       => $subtotal,
                    'fee_percentage' => $feePct,
                    'fee_amount'     => $feeAmount,
                    'total'          => $total,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
