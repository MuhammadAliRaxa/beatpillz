<?php

namespace App\Http\Controllers\Reviewer;

use App\Events\ItemApproved;
use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorItemHardRejectedNotification;
use App\Jobs\Author\SendAuthorItemSoftRejectedNotification;
use App\Models\Item;
use App\Models\ItemHistory;
use Exception;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $pageTitle = translate('All Items');

        $categories = authReviewer()->categories;
        $items = $this->getItems();

        return view('reviewer.items.index', [
            'pageTitle' => $pageTitle,
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    public function status($status)
    {
        $pageTitle = translate(':status Items', [
            'status' => translate(ucwords(str_replace('-', ' ', $status))),
        ]);

        $status = $this->getStatusNumber($status);
        abort_if(!$status, 404);

        $categories = authReviewer()->categories;
        $items = $this->getItems($status);

        return view('reviewer.items.index', [
            'pageTitle' => $pageTitle,
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    private function getStatusNumber($statusName)
    {
        $statuses = [
            'pending' => Item::STATUS_PENDING,
            'soft-rejected' => Item::STATUS_SOFT_REJECTED,
            'resubmitted' => Item::STATUS_RESUBMITTED,
            'approved' => Item::STATUS_APPROVED,
            'hard-rejected' => Item::STATUS_HARD_REJECTED,
        ];

        return isset($statuses[$statusName]) ? $statuses[$statusName] : null;
    }

    private function getItems($status = null)
    {
        $items = Item::whereReviewerCategories(authReviewer())
            ->notDeleted();

        if ($status) {
            $items->where('status', $status);
        }

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $items->where(function ($query) use ($searchTerm) {
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
                        $query->Where('firstname', 'like', $searchTerm)
                            ->OrWhere('lastname', 'like', $searchTerm)
                            ->OrWhere('username', 'like', $searchTerm)
                            ->OrWhere('email', 'like', $searchTerm)
                            ->OrWhere('address', 'like', $searchTerm);
                    })
                    ->orWhereHas('category', function ($query) use ($searchTerm) {
                        $query->Where('name', 'like', $searchTerm)
                            ->OrWhere('slug', 'like', $searchTerm);
                    })
                    ->orWhereHas('subCategory', function ($query) use ($searchTerm) {
                        $query->Where('name', 'like', $searchTerm)
                            ->OrWhere('slug', 'like', $searchTerm);
                    });
            });
        }

        if (request()->filled('category')) {
            $category = authReviewer()->categories->where('slug', request('category'))->first();
            if ($category) {
                $items->where('category_id', $category->id);
            }
        }

        $items = $items->orderbyDesc('id')->paginate(30);
        $items->appends(request()->only(['search', 'category']));

        return $items;
    }

    public function review($id)
    {
        $item = Item::where('id', $id)->whereReviewerCategories(authReviewer())
            ->notDeleted()
            ->firstOrFail();
        return view('reviewer.items.review', ['item' => $item]);
    }

    public function history($id)
    {
        $item = Item::where('id', $id)->whereReviewerCategories(authReviewer())
            ->notDeleted()
            ->firstOrFail();

        $itemHistories = ItemHistory::where('item_id', $item->id)
            ->orderbyDesc('id')->paginate(10);

        return view('reviewer.items.history', [
            'item' => $item,
            'itemHistories' => $itemHistories,
        ]);
    }

    public function action($id)
    {
        $item = Item::where('id', $id)
            ->whereIn('status', [Item::STATUS_PENDING, Item::STATUS_RESUBMITTED])
            ->whereReviewerCategories(authReviewer())->firstOrFail();

        return view('reviewer.items.action', ['item' => $item]);
    }

    public function actionUpdate(Request $request, $id)
    {
        $item = Item::where('id', $id)
            ->whereIn('status', [Item::STATUS_PENDING, Item::STATUS_RESUBMITTED])
            ->whereReviewerCategories(authReviewer())->firstOrFail();

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
                return redirect()->route('reviewer.items.review', $item->id);
                break;
        }

        $itemHistory = new ItemHistory();
        $itemHistory->reviewer_id = authReviewer()->id;
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
        return redirect()->route('reviewer.items.review', $item->id);
    }

    public function download(Request $request, $id)
    {
        $item = Item::where('id', $id)->whereReviewerCategories(authReviewer())
            ->notDeleted()
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
}