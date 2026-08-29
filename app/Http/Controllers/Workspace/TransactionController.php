<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', authUser()->id)
            ->whereNot('status', Transaction::STATUS_UNPAID);

        if (!licenseType(2) || !@settings('premium')->status) {
            $transactions->whereNot('type', Transaction::TYPE_SUBSCRIPTION);
        }

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $transactions->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhere('amount', 'like', $searchTerm)
                    ->OrWhere('fees', 'like', $searchTerm)
                    ->OrWhere('total', 'like', $searchTerm)
                    ->OrWhereHas('paymentGateway', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm);
                    });
            });
        }

        if (request()->filled('type')) {
            $transactions->where('type', request('type'));
        }

        if (request()->filled('status')) {
            $transactions->where('status', request('status'));
        }

        $transactions = $transactions->with('paymentGateway')
            ->orderbyDesc('id')->paginate(20);

        $transactions->appends(request()->only(['search', 'type', 'status']));

        return theme_view('workspace.transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', authUser()->id)
            ->whereNot('status', Transaction::STATUS_UNPAID)
            ->with('paymentGateway')
            ->firstOrFail();

        abort_if($transaction->isTypeSubscription() &&
            (!licenseType(2) || !@settings('premium')->status), 404);

        return theme_view('workspace.transactions.show', [
            'trx' => $transaction,
        ]);
    }

    public function invoice($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', authUser()->id)
            ->paid()
            ->firstOrFail();

        abort_if($transaction->isTypeSubscription() &&
            (!licenseType(2) || !@settings('premium')->status), 404);

        return theme_view('workspace.transactions.invoice', [
            'trx' => $transaction,
        ]);
    }
}
