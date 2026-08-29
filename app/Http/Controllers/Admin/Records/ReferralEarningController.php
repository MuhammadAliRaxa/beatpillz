<?php

namespace App\Http\Controllers\Admin\Records;

use App\Http\Controllers\Controller;
use App\Models\ReferralEarning;
use Carbon\Carbon;

class ReferralEarningController extends Controller
{
    public function index()
    {
        $referralEarnings = ReferralEarning::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $referralEarnings->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhere('referral_id', 'like', $searchTerm)
                    ->OrWhereHas('sale', function ($query) use ($searchTerm) {
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
                        $query->Where('firstname', 'like', $searchTerm)
                            ->OrWhere('lastname', 'like', $searchTerm)
                            ->OrWhere('username', 'like', $searchTerm)
                            ->OrWhere('email', 'like', $searchTerm)
                            ->OrWhere('address', 'like', $searchTerm);
                    });
            });
        }

        if (request()->filled('author')) {
            $referralEarnings->where('author_id', request('author'));
        }

        if (request()->filled('date_from')) {
            $dateFrom = Carbon::parse(request('date_from'))->startOfDay();
            $referralEarnings->where('created_at', '>=', $dateFrom);
        }

        if (request()->filled('date_to')) {
            $dateTo = Carbon::parse(request('date_to'))->endOfDay();
            $referralEarnings->where('created_at', '<=', $dateTo);
        }

        $filteredReferralEarnings = $referralEarnings->get();
        $counters['active'] = $filteredReferralEarnings->where('status', ReferralEarning::STATUS_ACTIVE)->count();
        $counters['refunded'] = $filteredReferralEarnings->where('status', ReferralEarning::STATUS_REFUNDED)->count();
        $counters['cancelled'] = $filteredReferralEarnings->where('status', ReferralEarning::STATUS_CANCELLED)->count();

        $referralEarnings = $referralEarnings->orderbyDesc('id')->paginate(50);
        $referralEarnings->appends(request()->only(['search', 'author', 'date_from', 'date_to']));

        return view('admin.records.referral-earnings', [
            'counters' => $counters,
            'referralEarnings' => $referralEarnings,
        ]);
    }
}