<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\ItemView;
use App\Models\ReferralEarning;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class DashboardController extends Controller
{
    public function index()
    {
        if (request()->filled('period')) {
            $period = request()->input('period');
            $startDate = Date::parse($period)->startOfMonth();
            $endDate = Date::parse($period)->endOfMonth();
        } else {
            $startDate = Date::now()->startOfMonth();
            $endDate = Date::now()->endOfMonth();
        }

        $counters = $this->generateCounters($startDate, $endDate);
        $charts['sales'] = $this->generateSalesChartData($startDate, $endDate);
        $topSellingItems = $this->getTopSellingItems($startDate, $endDate);
        $topPurchasingCountries = $this->getTopPurchasingCountries($startDate, $endDate);
        $geoCountries = $this->getGeoCountries($startDate, $endDate);
        $charts['views'] = $this->generateViewsChartData($startDate, $endDate);
        $referrals = $this->generateReferralsData($startDate, $endDate);

        return theme_view('workspace.dashboard', [
            'counters' => $counters,
            'charts' => $charts,
            'topSellingItems' => $topSellingItems,
            'topPurchasingCountries' => $topPurchasingCountries,
            'geoCountries' => $geoCountries,
            'referrals' => $referrals,
        ]);
    }

    private function generateCounters($startDate, $endDate)
    {
        $sales = Sale::active()
            ->where('author_id', authUser()->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);

        $counters['total_sales'] = $sales->count();
        $counters['sales_earnings'] = $sales->sum('author_earning');

        $counters['referrals_earnings'] = ReferralEarning::active()
            ->where('author_id', authUser()->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->sum('author_earning');

        $counters['total_views'] = ItemView::whereHas('item', function ($query) {
            return $query->where('author_id', authUser()->id);
        })->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        return $counters;
    }

    private function generateSalesChartData($startDate, $endDate)
    {
        $chart['title'] = translate('Sales');
        $dates = chartDates($startDate, $endDate);

        $sales = Sale::active()
            ->where('author_id', authUser()->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $salesData = $dates->merge($sales);

        $chart['labels'] = [];
        $chart['data'] = [];
        foreach ($salesData as $date => $count) {
            $label = Date::parse($date)->format('d M');
            $chart['labels'][] = $label;
            $chart['data'][] = $count;
        }

        $chart['max'] = (max($chart['data']) > 9) ? max($chart['data']) + 2 : 10;

        return $chart;
    }

    public function getTopSellingItems($startDate, $endDate)
    {
        return Sale::active()
            ->where('author_id', authUser()->id)
            ->select('item_id', DB::raw('COUNT(*) as total_sales'))
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('item_id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(4)
            ->get();
    }

    private function getGeoCountries($startDate, $endDate)
    {
        return Sale::active()
            ->where('author_id', authUser()->id)
            ->whereNotNull('country')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('country', DB::raw('COUNT(*) as total_sales'))
            ->groupBy('country')
            ->orderbyDesc('total_sales')
            ->get();
    }

    private function getTopPurchasingCountries($startDate, $endDate)
    {
        return Sale::active()
            ->where('author_id', authUser()->id)
            ->whereNotNull('country')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('country', DB::raw('SUM(author_earning) as total_earnings'))
            ->groupBy('country')
            ->orderbyDesc('total_earnings')
            ->limit(6)
            ->get();
    }

    private function generateViewsChartData($startDate, $endDate)
    {
        $chart['title'] = translate('Views');
        $dates = chartDates($startDate, $endDate);

        $sales = ItemView::whereHas('item', function ($query) {
            return $query->where('author_id', authUser()->id);
        })->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $salesData = $dates->merge($sales);

        $chart['labels'] = [];
        $chart['data'] = [];
        foreach ($salesData as $date => $count) {
            $label = Date::parse($date)->format('d M');
            $chart['labels'][] = $label;
            $chart['data'][] = $count;
        }

        $chart['max'] = (max($chart['data']) > 9) ? max($chart['data']) + 2 : 10;

        return $chart;
    }

    private function generateReferralsData($startDate, $endDate)
    {
        return ItemView::whereHas('item', function ($query) {
            return $query->where('author_id', authUser()->id);
        })->whereNotNull('referrer')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('referrer', DB::raw('COUNT(*) as total_views'))
            ->groupBy('referrer')
            ->orderbyDesc('total_views')
            ->limit(10)
            ->get();
    }
}
