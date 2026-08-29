<?php

namespace App\Http\Controllers\Admin\Records;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Carbon\Carbon;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $refunds->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhereHas('purchase', function ($query) use ($searchTerm) {
                        $query->where('id', 'like', $searchTerm)
                            ->orWhereHas('item', function ($query) use ($searchTerm) {
                                $query->where('id', 'like', $searchTerm)
                                    ->OrWhere('name', 'like', $searchTerm)
                                    ->OrWhere('slug', 'like', $searchTerm)
                                    ->OrWhere('description', 'like', $searchTerm)
                                    ->OrWhere('options', 'like', $searchTerm)
                                    ->OrWhere('demo_link', 'like', $searchTerm)
                                    ->OrWhere('tags', 'like', $searchTerm)
                                    ->OrWhere('regular_price', 'like', $searchTerm)
                                    ->OrWhere('extended_price', 'like', $searchTerm);
                            });
                    })
                    ->orWhereHas('author', function ($query) use ($searchTerm) {
                        $query->where('id', 'like', $searchTerm)
                            ->OrWhere('firstname', 'like', $searchTerm)
                            ->OrWhere('lastname', 'like', $searchTerm)
                            ->OrWhere('username', 'like', $searchTerm)
                            ->OrWhere('email', 'like', $searchTerm)
                            ->OrWhere('address', 'like', $searchTerm);
                    })
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('id', 'like', $searchTerm)
                            ->OrWhere('firstname', 'like', $searchTerm)
                            ->OrWhere('lastname', 'like', $searchTerm)
                            ->OrWhere('username', 'like', $searchTerm)
                            ->OrWhere('email', 'like', $searchTerm)
                            ->OrWhere('address', 'like', $searchTerm);
                    });
            });
        }

        if (request()->filled('author')) {
            $refunds->where('author_id', request('author'));
        }

        if (request()->filled('user')) {
            $refunds->where('user_id', request('user'));
        }

        if (request()->filled('date_from')) {
            $dateFrom = Carbon::parse(request('date_from'))->startOfDay();
            $refunds->where('created_at', '>=', $dateFrom);
        }

        if (request()->filled('date_to')) {
            $dateTo = Carbon::parse(request('date_to'))->endOfDay();
            $refunds->where('created_at', '<=', $dateTo);
        }

        $filteredRefunds = $refunds->get();
        $counters['pending'] = $filteredRefunds->where('status', Refund::STATUS_PENDING)->count();
        $counters['accepted'] = $filteredRefunds->where('status', Refund::STATUS_ACCEPTED)->count();
        $counters['declined'] = $filteredRefunds->where('status', Refund::STATUS_DECLINED)->count();

        $refunds = $refunds->orderbyDesc('id')->paginate(50);
        $refunds->appends(request()->only(['search', 'author', 'user', 'date_from', 'date_to']));

        return view('admin.records.refunds.index', [
            'counters' => $counters,
            'refunds' => $refunds,
        ]);
    }

    public function show(Refund $refund)
    {
        return view('admin.records.refunds.show', ['refund' => $refund]);
    }

    public function destroy(Refund $refund)
    {
        $refund->delete();
        toastr()->success(translate('Deleted Successfully'));
        return redirect()->route('admin.records.refunds.index');
    }
}