<?php

namespace App\Http\Controllers\Admin\Records;

use App\Http\Controllers\Controller;
use App\Models\PremiumEarning;

class PremiumEarningController extends Controller
{
    public function index()
    {
        $premiumEarnings = PremiumEarning::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $premiumEarnings->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhere('subscription_id', 'like', $searchTerm)
                    ->OrWhere('name', 'like', $searchTerm)
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
            $premiumEarnings->where('author_id', request('author'));
        }

        if (request()->filled('date_from')) {
            $dateFrom = Carbon::parse(request('date_from'))->startOfDay();
            $premiumEarnings->where('created_at', '>=', $dateFrom);
        }

        if (request()->filled('date_to')) {
            $dateTo = Carbon::parse(request('date_to'))->endOfDay();
            $premiumEarnings->where('created_at', '<=', $dateTo);
        }

        $premiumEarnings = $premiumEarnings->orderbyDesc('id')->paginate(50);
        $premiumEarnings->appends(request()->only(['search', 'author', 'date_from', 'date_to']));

        return view('admin.records.premium-earnings.index', [
            'premiumEarnings' => $premiumEarnings,
        ]);
    }

    public function show(PremiumEarning $premiumEarning)
    {
        return view('admin.records.premium-earnings.show', [
            'premiumEarning' => $premiumEarning,
        ]);
    }

    public function destroy(PremiumEarning $premiumEarning)
    {
        $premiumEarning->delete();
        toastr()->success(translate('Deleted Successfully'));
        return redirect()->route('admin.records.premium-earnings.index');
    }
}