<?php

namespace App\Http\Controllers\Payments;

use App\Events\TransactionPaid;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaypalIpnController extends Controller
{
    private $paymentGateway;
    private $endpoint;

    public function __construct()
    {
        $this->paymentGateway = paymentGateway('paypal_ipn');
        $this->endpoint = $this->paymentGateway->isSandboxMode() ?
        'https://www.sandbox.paypal.com/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
    }

    public function process($trx)
    {
        $gateway = $this->paymentGateway;

        $amount = amountFormat($gateway->getChargeAmount($trx->amount));
        $fees = amountFormat($gateway->getChargeAmount($trx->fees));
        $tax = $trx->tax ? amountFormat($gateway->getChargeAmount($trx->tax->amount)) : 0;
        $total = amountFormat($amount + $fees + $tax);

        $body = [
            'cmd' => '_xclick',
            'business' => trim($this->paymentGateway->credentials->email),
            'cbt' => @settings('general')->site_name,
            'currency_code' => $this->paymentGateway->getCurrency(),
            'quantity' => 1,
            'item_name' => translate('Payment for order No.:number', [
                'number' => $trx->id,
            ]),
            'custom' => "$trx->id",
            'amount' => $amount,
            'tax' => $tax,
            'handling' => $fees,
            'return' => route('payments.ipn.paypal-ipn', ['id' => hash_encode($trx->id)]),
            'cancel_return' => route('checkout.index', hash_encode($trx->id)),
            'notify_url' => route('payments.notifications.paypal-ipn'),
        ];

        try {
            $response = Http::asForm()->post($this->endpoint, $body);

            if (!$response->successful()) {
                throw new Exception(translate('An error occurred while calling the API'));
            }

            $redirectUrl = $this->endpoint . '?' . http_build_query($body);

            $data['type'] = "success";
            $data['method'] = "redirect";
            $data['redirect_url'] = $redirectUrl;
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

    public function notification(Request $request)
    {
        try {
            $payload = $request->all();
            if (!$payload) {
                return response('Invalid payload', 401);
            }

            $payload['cmd'] = '_notify-validate';

            $response = Http::asForm()->post(
                $this->endpoint,
                $payload
            );

            if ($response->body() === 'VERIFIED') {
                $paymentStatus = $payload['payment_status'] ?? null;
                $trxId = $payload['custom'] ?? null;
                $paymentId = $payload['txn_id'];
                $payerId = $payload['payer_id'] ?? null;
                $payerEmail = $payload['payer_email'] ?? null;

                if ($paymentStatus === 'Completed') {
                    $trx = Transaction::where('id', $trxId)->unpaid()->first();
                    if ($trx) {
                        $trx->payment_id = $paymentId;
                        $trx->payer_id = $payerId;
                        $trx->payer_email = $payerEmail;
                        $trx->status = Transaction::STATUS_PAID;
                        $trx->update();
                        event(new TransactionPaid($trx));
                    }
                }

                return response('Notification Verified', 200);
            } elseif ($response->body() === 'INVALID') {
                return response('Notification Invalid', 400);
            } else {
                return response('Notification Error', 500);
            }
        } catch (Exception $e) {
            return response('Notification Error', 500);
        }
    }
}