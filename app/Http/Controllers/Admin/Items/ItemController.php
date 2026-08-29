<?php

namespace App\Http\Controllers\Admin\Items;

use App\Events\ItemApproved;
use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorItemHardRejectedNotification;
use App\Jobs\Author\SendAuthorItemSoftRejectedNotification;
use App\Models\Badge;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\ItemHistory;
use App\Models\ItemReview;
use App\Models\ItemView;
use App\Models\Sale;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Date\Date;

class ItemController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $items = Item::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $searchTermStart = request('search') . '%';

            $items->where(function ($query) use ($searchTerm, $searchTermStart) {
                $query->where('name', 'like', $searchTermStart)
                    ->orWhere(function ($query) use ($searchTerm) {
                        $query->where('id', 'like', $searchTerm)
                            ->OrWhere('name', 'like', $searchTerm)
                            ->OrWhere('slug', 'like', $searchTerm)
                            ->OrWhere('description', 'like', $searchTerm)
                            ->OrWhere('options', 'like', $searchTerm)
                            ->OrWhere('demo_link', 'like', $searchTerm)
                            ->OrWhere('tags', 'like', $searchTerm)
                            ->OrWhere('regular_price', 'like', $searchTerm)
                            ->OrWhere('extended_price', 'like', $searchTerm)
                            ->orWhereHas('author', function ($query) use ($searchTerm) {
                                $query->where('firstname', 'like', $searchTerm)
                                    ->OrWhere('lastname', 'like', $searchTerm)
                                    ->OrWhere('username', 'like', $searchTerm)
                                    ->OrWhere('email', 'like', $searchTerm)
                                    ->OrWhere('address', 'like', $searchTerm);
                            })
                            ->orWhereHas('category', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm)
                                    ->OrWhere('slug', 'like', $searchTerm);
                            })
                            ->orWhereHas('subCategory', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm)
                                    ->OrWhere('slug', 'like', $searchTerm);
                            });
                    });
            });
        }

        if (request()->filled('author')) {
            $items->where('author_id', request('author'));
        }

        if (request()->filled('category')) {
            $items->where('category_id', request('category'));
        }

        if (request()->filled('status')) {
            $items->where('status', request('status'));
        }

        $filteredItems = $items->get();
        $counters['pending'] = $filteredItems->where('status', Item::STATUS_PENDING)->count();
        $counters['soft_rejected'] = $filteredItems->where('status', Item::STATUS_SOFT_REJECTED)->count();
        $counters['resubmitted'] = $filteredItems->where('status', Item::STATUS_RESUBMITTED)->count();
        $counters['approved'] = $filteredItems->where('status', Item::STATUS_APPROVED)->count();
        $counters['hard_rejected'] = $filteredItems->where('status', Item::STATUS_HARD_REJECTED)->count();
        $counters['deleted'] = $filteredItems->where('status', Item::STATUS_DELETED)->count();

        if (isset($searchTermStart)) {
            $items->orderByRaw("CASE WHEN name LIKE ? THEN 1 ELSE 2 END", [$searchTermStart])->orderByDesc('id');
        } else {
            $items->orderByDesc('id');
        }

        $items = $items->paginate(50);
        $items->appends(request()->only(['search', 'author', 'category', 'status']));

        return view('admin.items.index', [
            'counters' => $counters,
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    public function makeFeatured(Request $request, $id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $item->is_featured = Item::FEATURED;
        $item->was_featured = Item::FEATURED;
        $item->update();

        $badge = Badge::where('alias', Badge::FEATURED_ITEM_BADGE_ALIAS)->first();
        if ($badge) {
            $item->author->addBadge($badge);
        }

        toastr()->success(translate('The item marked as featured successfully'));
        return back();
    }

    public function removeFeatured(Request $request, $id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $item->is_featured = Item::NOT_FEATURED;
        $item->update();

        toastr()->success(translate('The item marked as not featured successfully'));
        return back();
    }

    public function makePremium(Request $request, $id)
    {
        $item = Item::where('id', $id)->notFree()->firstOrFail();

        $item->is_premium = Item::PREMIUM;
        $item->update();

        $badge = Badge::where('alias', Badge::PREMIUMER_ALIAS)->first();
        if ($badge) {
            $item->author->addBadge($badge);
        }

        toastr()->success(translate('The item added to premium successfully'));
        return back();
    }

    public function removePremium(Request $request, $id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $item->is_premium = Item::NOT_PREMIUM;
        $item->update();

        toastr()->success(translate('The item removed from premium successfully'));
        return back();
    }

    public function show($id)
    {
        $item = Item::where('id', $id)->firstOrFail();
        return view('admin.items.show', [
            'item' => $item,
        ]);
    }

    public function history($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $itemHistories = ItemHistory::where('item_id', $item->id)
            ->orderbyDesc('id')->paginate(10);

        return view('admin.items.history', [
            'item' => $item,
            'itemHistories' => $itemHistories,
        ]);
    }

    public function historyDelete($id, $history_id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $itemHistory = ItemHistory::where('id', $history_id)
            ->where('item_id', $item->id)->firstOrFail();

        $itemHistory->delete();

        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function discount($id)
    {
        $item = Item::where('id', $id)->firstOrFail();
        return view('admin.items.discount', [
            'item' => $item,
        ]);
    }

    public function discountDelete($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        if ($item->hasDiscount()) {
            $discount = $item->discount;
            $discount->delete();
        }

        $item->is_on_discount = Item::DISCOUNT_OFF;
        $item->update();

        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function action($id)
    {
        $item = Item::where('id', $id)
            ->whereIn('status', [Item::STATUS_PENDING, Item::STATUS_RESUBMITTED])
            ->firstOrFail();

        return view('admin.items.action', [
            'item' => $item,
        ]);
    }

    public function actionUpdate(Request $request, $id)
    {
        $item = Item::where('id', $id)
            ->whereIn('status', [Item::STATUS_PENDING, Item::STATUS_RESUBMITTED])
            ->firstOrFail();

        switch ($request->action) {
            case 'approve':
                $status = Item::STATUS_APPROVED;
                $title = $item->isPending() ? ItemHistory::TITLE_SUBMISSION_APPROVED : ItemHistory::TITLE_RESUBMISSION_APPROVED;
                $success = translate('The item has been approved');

                break;
            case 'soft_reject':
                if (empty($request->reason)) {
                    toastr()->error(translate('The soft rejection reason is required'));
                    return back();
                }

                $status = Item::STATUS_SOFT_REJECTED;
                $title = ItemHistory::TITLE_SOFT_REJECTION;
                $success = translate('The item has been soft rejected');

                break;
            case 'hard_reject':
                $status = Item::STATUS_HARD_REJECTED;
                $title = ItemHistory::TITLE_HARD_REJECTION;
                $success = translate('The item has been rejected');

                break;
            default:
                return redirect()->route('admin.items.show', $item->id);
                break;
        }

        $itemHistory = new ItemHistory();
        $itemHistory->admin_id = authAdmin()->id;
        $itemHistory->item_id = $item->id;
        $itemHistory->title = $title;
        $itemHistory->body = $request->reason ?? null;
        $itemHistory->save();

        $item->status = $status;
        $item->update();

        if ($item->isApproved()) {
            event(new ItemApproved($item));
        } elseif ($item->isSoftRejected()) {
            dispatch(new SendAuthorItemSoftRejectedNotification($item, $itemHistory));
        } elseif ($item->isHardRejected()) {
            dispatch(new SendAuthorItemHardRejectedNotification($item));
        }

        toastr()->success($success);
        return redirect()->route('admin.items.show', $item->id);
    }

    public function status($id)
    {
        $item = Item::where('id', $id)->firstOrFail();
        return view('admin.items.status', [
            'item' => $item,
        ]);
    }

    public function statusUpdate(Request $request, $id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $item->status = $request->status;
        $item->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function statistics($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        if (request()->filled('period')) {
            $period = request()->input('period');
            $startDate = Date::parse($period)->startOfMonth();
            $endDate = Date::parse($period)->endOfMonth();
        } else {
            $startDate = Date::now()->startOfMonth();
            $endDate = Date::now()->endOfMonth();
        }

        $counters = $this->generateCounters($item, $startDate, $endDate);
        $charts['sales'] = $this->generateSalesChartData($item, $startDate, $endDate);
        $topPurchasingCountries = $this->getTopPurchasingCountries($item, $startDate, $endDate);
        $geoCountries = $this->getGeoCountries($item, $startDate, $endDate);
        $charts['views'] = $this->generateViewsChartData($item, $startDate, $endDate);
        $referrals = $this->generateReferralsData($item, $startDate, $endDate);

        return view('admin.items.statistics', [
            'item' => $item,
            'counters' => $counters,
            'charts' => $charts,
            'topPurchasingCountries' => $topPurchasingCountries,
            'geoCountries' => $geoCountries,
            'referrals' => $referrals,
        ]);
    }

    public function reviews($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $reviews = ItemReview::where('item_id', $id);

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $reviews->where('id', 'like', $searchTerm)
                ->OrWhere('body', 'like', $searchTerm)
                ->orWhereHas('user', function ($query) use ($searchTerm) {
                    $query->where('firstname', 'like', $searchTerm)
                        ->OrWhere('lastname', 'like', $searchTerm)
                        ->OrWhere('username', 'like', $searchTerm)
                        ->OrWhere('email', 'like', $searchTerm)
                        ->OrWhere('address', 'like', $searchTerm);
                });
        }

        $reviews = $reviews->with('user')->orderbyDesc('id')->paginate(10);
        $reviews->appends(request()->only(['search']));

        return view('admin.items.reviews', [
            'item' => $item,
            'reviews' => $reviews,
        ]);
    }

    public function reviewsDelete($id, $review_id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $review = ItemReview::where('id', $review_id)
            ->where('item_id', $id)
            ->firstOrFail();

        $review->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    public function comments($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $comments = ItemComment::where('item_id', $id);

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $comments->where('id', 'like', $searchTerm)
                ->orWhereHas('replies', function ($query) use ($searchTerm) {
                    $query->where('id', 'like', $searchTerm)
                        ->OrWhere('body', 'like', $searchTerm);
                })
                ->orWhereHas('user', function ($query) use ($searchTerm) {
                    $query->where('firstname', 'like', $searchTerm)
                        ->OrWhere('lastname', 'like', $searchTerm)
                        ->OrWhere('username', 'like', $searchTerm)
                        ->OrWhere('email', 'like', $searchTerm)
                        ->OrWhere('address', 'like', $searchTerm);
                });
        }

        $comments = $comments->with('user')->orderbyDesc('id')->paginate(10);
        $comments->appends(request()->only(['search']));

        return view('admin.items.comments', [
            'item' => $item,
            'comments' => $comments,
        ]);
    }

    public function commentsDelete($id, $comment_id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $comment = ItemComment::where('id', $comment_id)
            ->where('item_id', $id)
            ->firstOrFail();

        $comment->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }

    private function generateCounters($item, $startDate, $endDate)
    {
        $sales = Sale::active()
            ->where('item_id', $item->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);

        $counters['total_sales'] = $sales->count();
        $counters['total_sales_amount'] = $sales->sum('price');
        $counters['total_earnings'] = $sales->sum('author_earning');

        $counters['total_views'] = ItemView::where('item_id', $item->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        return $counters;
    }

    private function generateSalesChartData($item, $startDate, $endDate)
    {
        $chart['title'] = translate('Sales');
        $dates = chartDates($startDate, $endDate);

        $sales = Sale::active()
            ->where('item_id', $item->id)
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

    private function getGeoCountries($item, $startDate, $endDate)
    {
        return Sale::active()
            ->where('item_id', $item->id)
            ->whereNotNull('country')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('country', DB::raw('COUNT(*) as total_sales'))
            ->groupBy('country')
            ->orderbyDesc('total_sales')
            ->get();
    }

    private function getTopPurchasingCountries($item, $startDate, $endDate)
    {
        return Sale::active()
            ->where('item_id', $item->id)
            ->whereNotNull('country')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('country', DB::raw('SUM(price) as total_spend'))
            ->groupBy('country')
            ->orderbyDesc('total_spend')
            ->limit(6)
            ->get();
    }

    private function generateViewsChartData($item, $startDate, $endDate)
    {
        $chart['title'] = translate('Views');
        $dates = chartDates($startDate, $endDate);

        $sales = ItemView::where('item_id', $item->id)
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

    private function generateReferralsData($item, $startDate, $endDate)
    {
        return ItemView::where('item_id', $item->id)
            ->whereNotNull('referrer')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select('referrer', DB::raw('COUNT(*) as total_views'))
            ->groupBy('referrer')
            ->orderbyDesc('total_views')
            ->limit(10)
            ->get();
    }

    public function download($id)
    {
        $item = Item::where('id', $id)->notDeleted()
            ->firstOrFail();

        try {
            $response = $item->download();
            if (isset($response->type) && $response->type == "error") {
                throw new Exception($response->message);
            }
            return $response;
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function softDelete($id)
    {
        $item = Item::where('id', $id)->notDeleted()
            ->firstOrFail();

        $item->softDelete();
        toastr()->success(translate('Item has marked as deleted successfully'));
        return back();
    }

    public function permanentlyDelete($id)
    {
        $item = Item::where('id', $id)->firstOrFail();

        $item->delete();
        toastr()->success(translate('Item has permanently deleted successfully'));
        return redirect()->route('admin.items.index');
    }
}