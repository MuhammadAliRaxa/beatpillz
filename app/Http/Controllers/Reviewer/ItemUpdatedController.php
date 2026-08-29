<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorItemUpdateApprovedNotification;
use App\Jobs\Author\SendAuthorItemUpdateRejectedNotification;
use App\Jobs\SendBuyersItemUpdateNotification;
use App\Models\ItemHistory;
use App\Models\ItemUpdate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ItemUpdatedController extends Controller
{
    public function index()
    {
        $categories = authReviewer()->categories;

        $itemUpdates = ItemUpdate::whereReviewerCategories(authReviewer());

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $itemUpdates->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->OrWhere('name', 'like', $searchTerm)
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
                $itemUpdates->where('category_id', $category->id);
            }
        }

        $itemUpdates = $itemUpdates->orderbyDesc('id')->paginate(30);
        $itemUpdates->appends(request()->only(['search', 'category']));

        return view('reviewer.items.updated.index', [
            'categories' => $categories,
            'itemUpdates' => $itemUpdates,
        ]);
    }

    public function review($id)
    {
        $itemUpdate = ItemUpdate::where('id', $id)->whereReviewerCategories(authReviewer())
            ->firstOrFail();

        return view('reviewer.items.updated.review', ['itemUpdate' => $itemUpdate]);
    }

    public function history($id)
    {
        $itemUpdate = ItemUpdate::where('id', $id)->whereReviewerCategories(authReviewer())
            ->firstOrFail();

        $itemHistories = ItemHistory::where('item_id', $itemUpdate->item->id)
            ->orderbyDesc('id')->paginate(10);

        return view('reviewer.items.updated.history', [
            'itemUpdate' => $itemUpdate,
            'itemHistories' => $itemHistories,
        ]);
    }

    public function action($id)
    {
        $itemUpdate = ItemUpdate::where('id', $id)->whereReviewerCategories(authReviewer())
            ->firstOrFail();

        return view('reviewer.items.updated.action', ['itemUpdate' => $itemUpdate]);
    }

    public function actionUpdate(Request $request, $id)
    {
        $itemUpdate = ItemUpdate::where('id', $id)->whereReviewerCategories(authReviewer())
            ->firstOrFail();

        $item = $itemUpdate->item;

        try {
            switch ($request->action) {
                case 'approve':
                    $updated = false;
                    $itemClone = clone $item;

                    $item->name = $itemUpdate->name;
                    $item->description = $itemUpdate->description;
                    $item->options = $itemUpdate->options;
                    $item->version = $itemUpdate->version;
                    $item->demo_link = $itemUpdate->demo_link;
                    $item->tags = $itemUpdate->tags;

                    if ($itemUpdate->regular_price) {
                        $item->regular_price = $itemUpdate->regular_price;
                    }

                    if ($itemUpdate->extended_price) {
                        $item->extended_price = $itemUpdate->extended_price;
                    }

                    if ($itemUpdate->thumbnail) {
                        $item->thumbnail = $itemUpdate->thumbnail;
                    }

                    if ($itemUpdate->preview_image) {
                        $item->preview_image = $itemUpdate->preview_image;
                    }

                    if ($itemUpdate->preview_video) {
                        $item->preview_video = $itemUpdate->preview_video;
                    }

                    if ($itemUpdate->preview_audio) {
                        $item->preview_audio = $itemUpdate->preview_audio;
                    }

                    if ($itemUpdate->main_file) {
                        $item->main_file = $itemUpdate->main_file;
                        $item->is_main_file_external = $itemUpdate->is_main_file_external;
                        $item->last_update_at = Carbon::now();
                        $updated = true;
                    }

                    if ($itemUpdate->screenshots) {
                        $item->screenshots = $itemUpdate->screenshots;
                    }

                    $item->purchasing_status = $itemUpdate->purchasing_status;
                    $item->is_free = $itemUpdate->is_free;
                    $item->is_supported = $itemUpdate->is_supported;
                    $item->support_instructions = $itemUpdate->support_instructions;
                    $item->update();

                    if ($updated) {
                        dispatch(new SendBuyersItemUpdateNotification($item));
                    }

                    if ($itemUpdate->thumbnail) {
                        $itemClone->deleteThumbnail();
                    }

                    if ($itemUpdate->preview_image) {
                        $itemClone->deletePreviewImage();
                    }

                    if ($itemUpdate->preview_video) {
                        $itemClone->deletePreviewVideo();
                    }

                    if ($itemUpdate->preview_audio) {
                        $itemClone->deletePreviewAudio();
                    }

                    if ($itemUpdate->main_file) {
                        $itemClone->deleteMainFile();
                    }

                    if ($itemUpdate->screenshots) {
                        $itemClone->deleteScreenshots();
                    }

                    $itemHistory = new ItemHistory();
                    $itemHistory->reviewer_id = authReviewer()->id;
                    $itemHistory->item_id = $item->id;
                    $itemHistory->title = ItemHistory::TITLE_UPDATE_APPROVED;
                    $itemHistory->save();

                    $itemUpdate->delete();
                    dispatch(new SendAuthorItemUpdateApprovedNotification($item));
                    $success = translate('The update request has been approved');
                    break;
                case 'reject':
                    if (empty($request->reason)) {
                        toastr()->error(translate('The rejection reason is required'));
                        return back();
                    }

                    $itemHistory = new ItemHistory();
                    $itemHistory->reviewer_id = authReviewer()->id;
                    $itemHistory->item_id = $item->id;
                    $itemHistory->title = ItemHistory::TITLE_UPDATE_REJECTED;
                    $itemHistory->body = $request->reason ?? null;
                    $itemHistory->save();

                    $itemUpdate->deleteFiles();
                    $itemUpdate->delete();
                    dispatch(new SendAuthorItemUpdateRejectedNotification($item, $itemHistory));
                    $success = translate('The update request has been rejected');
                    break;
                default:
                    return redirect()->route('reviewer.items.updated.index');
                    break;
            }

            toastr()->success($success);
            return redirect()->route('reviewer.items.updated.index');
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function download(Request $request, $id)
    {
        $itemUpdate = ItemUpdate::where('id', $id)
            ->whereNotNull('main_file')
            ->whereReviewerCategories(authReviewer())
            ->firstOrFail();

        try {
            $response = $itemUpdate->download();
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