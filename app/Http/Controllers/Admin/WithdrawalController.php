<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorWithdrawalStatusUpdateNotification;
use App\Models\Statement;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawalMethods = WithdrawalMethod::all();

        $withdrawals = Withdrawal::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $withdrawals->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->orWhere('author_id', 'like', $searchTerm)
                    ->orWhere('account', 'like', $searchTerm)
                    ->orWhere('method', 'like', $searchTerm);
            });
        }

        if (request()->filled('date_from')) {
            $dateFrom = Carbon::parse(request('date_from'))->startOfDay();
            $withdrawals->where('created_at', '>=', $dateFrom);
        }

        if (request()->filled('date_to')) {
            $dateTo = Carbon::parse(request('date_to'))->endOfDay();
            $withdrawals->where('created_at', '<=', $dateTo);
        }

        if (request()->filled('author')) {
            $withdrawals->where('author_id', request('author'));
        }

        if (request()->filled('status')) {
            $withdrawals->where('status', request('status'));
        }

        if (request()->filled('withdrawal_method')) {
            $withdrawals->where('method', request('withdrawal_method'));
        }

        $filteredWithdrawals = $withdrawals->get();

        $counters['pending']['total'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_PENDING)->count();
        $counters['pending']['amount'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_PENDING)->sum('amount');
        $counters['returned']['total'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_RETURNED)->count();
        $counters['returned']['amount'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_RETURNED)->sum('amount');
        $counters['approved']['total'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_APPROVED)->count();
        $counters['approved']['amount'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_APPROVED)->sum('amount');
        $counters['completed']['total'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_COMPLETED)->count();
        $counters['completed']['amount'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_COMPLETED)->sum('amount');
        $counters['cancelled']['total'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_CANCELLED)->count();
        $counters['cancelled']['amount'] = $filteredWithdrawals->where('status', Withdrawal::STATUS_CANCELLED)->sum('amount');

        $withdrawals = $withdrawals->orderByDesc('id')->paginate(50);
        $withdrawals->appends(request()->only(['search', 'date_from', 'date_to', 'author', 'status', 'withdrawal_method']));

        return view('admin.withdrawals.index', [
            'counters' => $counters,
            'withdrawalMethods' => $withdrawalMethods,
            'withdrawals' => $withdrawals,
        ]);
    }

    public function review(Withdrawal $withdrawal)
    {
        return view('admin.withdrawals.review', ['withdrawal' => $withdrawal]);
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        abort_if(in_array($withdrawal->status, [
            Withdrawal::STATUS_RETURNED,
            Withdrawal::STATUS_COMPLETED,
            Withdrawal::STATUS_CANCELLED,
        ]), 401);

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if ($request->status == $withdrawal->status) {
            toastr()->info(translate('The status is not changed'));
            return back();
        }

        if ($request->has('email_notification')) {
            if (!@settings('smtp')->status) {
                toastr()->error(translate('SMTP is not enabled'));
                return back();
            }
            if (!mailTemplate('author_withdrawal_status_updated')->status) {
                toastr()->error(translate('Email template is disabled from mail templates'));
                return back();
            }
        }

        $withdrawal->status = $request->status;
        $withdrawal->update();

        $author = $withdrawal->author;

        if ($withdrawal->isReturned()) {
            $author->increment('balance', $withdrawal->amount);
        }

        if ($request->has('email_notification')) {
            dispatch(new SendAuthorWithdrawalStatusUpdateNotification($withdrawal));
        }

        if ($withdrawal->isCompleted()) {
            $saleStatement = new Statement();
            $saleStatement->user_id = $withdrawal->author->id;
            $saleStatement->title = translate('[Withdrawal] #:id', ['id' => $withdrawal->id]);
            $saleStatement->amount = $withdrawal->amount;
            $saleStatement->total = $withdrawal->amount;
            $saleStatement->type = Statement::TYPE_DEBIT;
            $saleStatement->save();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

}
