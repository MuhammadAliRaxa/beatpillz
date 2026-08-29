<?php

namespace App\Http\Controllers\Payments;

use App\Events\TransactionPaid;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use CoinGate\Client;
use Illuminate\Http\Request;

class CoingateController extends Controller
{
    private $paymentGateway;
    private $client;

    public function __construct()
    {
        $this->paymentGateway = paymentGateway('coingate');
        $this->client = new Client($this->paymentGateway->credentials->auth_token, true);
    }

    public function process($trx)
    {
        $currency = $this->paymentGateway->getCurrency();

        $body = [
            'title' => translate('Payment for order #:number', [
                'number' => $trx->id,
            ]),
            'order_id' => $trx->id,
            'price_amount' => amountFormat($this->paymentGateway->getChargeAmount($trx->total)),
            'price_currency' => $currency,
            'receive_currency' => $currency,
            'callback_url' => route('payments.webhooks.coingate'),
            'success_url' => route('payments.ipn.coingate', ['id' => hash_encode($trx->id)]),
            'cancel_url' => route('checkout.index', hash_encode($trx->id)),
        ];

        try {
            $order = $this->client->order->create($body);
            if (!$order) {
                throw new Exception(translate('An error occurred while calling the API'));
            }

            $trx->payment_id = $order->id;
            $trx->update();

            $data['type'] = "success";
            $data['method'] = "redirect";
            $data['redirect_url'] = $order->payment_url;
        } catch (\Exception $e) {
            $data['type'] = "error";
            $data['msg'] = $e->getMessage();
        }

        return json_encode($data);
    }

    public function ipn(Request $request)
    {
        $maxAttempts = 10;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $trx = Transaction::where('id', hash_decode($request->id))
                ->where('user_id', authUser()->id)
                ->whereIn('status', [Transaction::STATUS_PAID, Transaction::STATUS_UNPAID])
                ->firstOrFail();

            if ($trx->isPaid()) {
                $trx->user->emptyCart();
                return redirect()->route('checkout.index', hash_encode($trx->id));
            }

            sleep(5);
            $attempt++;
        }

        toastr()->warning(translate("Your payment is being processed and you will get email notification when it's completed"));
        return redirect()->route('home');
    }

    public function webhook(Request $request)
    {
        try {
            if ($request->status == 'paid') {
                $trx = Transaction::where('id', $request->order_id)
                    ->where('payment_id', $request->id)
                    ->unpaid()
                    ->first();

                if ($trx) {
                    $trx->status = Transaction::STATUS_PAID;
                    $trx->update();
                    event(new TransactionPaid($trx));
                }
            }

            return response('Webhook processed successfully', 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}