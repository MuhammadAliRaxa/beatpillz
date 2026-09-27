<?php

namespace App\Http\Controllers\Workspace;

use App\Events\WithdrawalSubmitted;
use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $author = authUser();

        $counters['pending_withdrawals'] = Withdrawal::where('author_id', $author->id)
            ->pending()->sum('amount');
        $counters['total_withdrawals'] = Withdrawal::where('author_id', $author->id)
            ->whereIn('status', [Withdrawal::STATUS_APPROVED, Withdrawal::STATUS_COMPLETED])
            ->sum('amount');

        $withdrawals = Withdrawal::where('author_id', $author->id);

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $withdrawals->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhere('method', 'like', $searchTerm)
                    ->OrWhere('account', 'like', $searchTerm);
            });
        }

        if (request()->filled('status')) {
            $withdrawals->where('status', request('status'));
        }

        $withdrawals = $withdrawals->orderbyDesc('id')->paginate(20);
        $withdrawals->appends(request()->only(['search', 'status']));

        return theme_view('workspace.withdrawals', [
            'counters' => $counters,
            'withdrawals' => $withdrawals,
        ]);
    }

    public function withdraw(Request $request)
    {
        $author = authUser();

        if ($author->balance < $author->withdrawalMethod->minimum) {
            toastr()->error(translate('Your balance is less than the minimum withdrawal limit'));
            return back();
        }

        $amount = $author->balance;

        $withdrawal = new Withdrawal();
        $withdrawal->author_id = $author->id;
        $withdrawal->amount = $amount;
        $withdrawal->method = $author->withdrawalMethod->name;
        $withdrawal->account = $author->withdrawal_account;
        $withdrawal->status = Withdrawal::STATUS_PENDING;
        $withdrawal->save();

        $author->decrement('balance', $amount);

        event(new WithdrawalSubmitted($withdrawal));

        toastr()->success(translate('Your withdrawal request has been sent successfully'));
        return back();
    }

}