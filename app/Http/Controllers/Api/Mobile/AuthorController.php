<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\WithdrawalSubmitted;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ItemResource;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Get Producer dashboard statistics.
     */
    public function dashboard(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->is_author) {
            return response()->json([
                'success' => false,
                'message' => 'You must be registered as an author to access this studio.',
            ], 403);
        }

        $itemsCount = Item::where('author_id', $author->id)->count();
        $approvedItemsCount = Item::where('author_id', $author->id)->where('status', Item::STATUS_APPROVED)->count();
        $pendingItemsCount = Item::where('author_id', $author->id)->where('status', Item::STATUS_PENDING)->count();

        $pendingWithdrawals = Withdrawal::where('author_id', $author->id)
            ->where('status', Withdrawal::STATUS_PENDING)
            ->sum('amount');

        return response()->json([
            'success' => true,
            'data'    => [
                'balance'              => (float) $author->balance,
                'total_sales'          => (int) $author->total_sales,
                'total_sales_amount'   => (float) $author->total_sales_amount,
                'total_reviews'        => (int) $author->total_reviews,
                'avg_reviews'          => (float) $author->avg_reviews,
                'total_followers'      => (int) $author->total_followers,
                'pending_withdrawals'  => (float) $pendingWithdrawals,
                'items_count'          => (int) $itemsCount,
                'approved_items_count' => (int) $approvedItemsCount,
                'pending_items_count'  => (int) $pendingItemsCount,
            ],
        ], 200);
    }

    /**
     * List all items uploaded by the author with statuses.
     */
    public function items(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $query = Item::where('author_id', $author->id)->with(['category', 'discount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('id', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $items->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'slug'          => $item->slug,
                    'status'        => (int) $item->status,
                    'status_name'   => $item->getStatusName(),
                    'regular_price' => (float) $item->regular_price,
                    'total_sales'   => (int) $item->total_sales,
                    'thumbnail_url' => $item->getThumbnailLink(),
                    'created_at'    => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ], 200);
    }

    /**
     * List author sales.
     */
    public function sales(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $sales = Sale::where('author_id', $author->id)
            ->with(['item', 'buyer'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $sales->map(function ($sale) {
                return [
                    'id'            => $sale->id,
                    'price'         => (float) $sale->price,
                    'author_earning'=> (float) $sale->author_earning,
                    'license_type'  => (int) $sale->license_type,
                    'item'          => $sale->item ? [
                        'id'   => $sale->item->id,
                        'name' => $sale->item->name,
                        'slug' => $sale->item->slug,
                    ] : null,
                    'buyer'         => $sale->buyer ? [
                        'name'     => $sale->buyer->getName(),
                        'username' => $sale->buyer->username,
                    ] : null,
                    'created_at'    => $sale->created_at ? $sale->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $sales->currentPage(),
                'last_page'    => $sales->lastPage(),
                'per_page'     => $sales->perPage(),
                'total'        => $sales->total(),
            ],
        ], 200);
    }

    /**
     * Get withdrawal methods and author payout history.
     */
    public function withdrawals(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $methods = WithdrawalMethod::all();
        $withdrawals = Withdrawal::where('author_id', $author->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => [
                'available_methods' => $methods->map(function ($m) {
                    return [
                        'id'      => $m->id,
                        'name'    => $m->name,
                        'minimum' => (float) $m->minimum,
                    ];
                }),
                'current_method'    => $author->withdrawalMethod ? [
                    'id'      => $author->withdrawalMethod->id,
                    'name'    => $author->withdrawalMethod->name,
                    'account' => $author->withdrawal_account,
                ] : null,
                'history'           => $withdrawals->map(function ($w) {
                    return [
                        'id'         => $w->id,
                        'amount'     => (float) $w->amount,
                        'method'     => $w->method,
                        'account'    => $w->account,
                        'status'     => (int) $w->status,
                        'created_at' => $w->created_at ? $w->created_at->toISOString() : null,
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * Submit a withdrawal payout request.
     */
    public function requestWithdrawal(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->hasWithdrawalAccount()) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure your payout withdrawal account in your profile first.',
            ], 400);
        }

        if ($author->balance < $author->withdrawalMethod->minimum) {
            return response()->json([
                'success' => false,
                'message' => 'Your balance is below the minimum withdrawal limit of ' . $author->withdrawalMethod->minimum,
            ], 400);
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

        try {
            event(new WithdrawalSubmitted($withdrawal));
        } catch (\Throwable $th) {}

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully.',
        ], 200);
    }

    /**
     * Upload a new beat / item.
     */
    /**
     * Upload a new beat / item with full metadata, category attributes, files, and licensing.
     */
    public function uploadBeat(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->is_author) {
            return response()->json([
                'success' => false,
                'message' => 'Only registered authors can upload beats.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'                   => ['required', 'string', 'max:150'],
            'description'            => ['required', 'string'],
            'category_id'            => ['nullable'],
            'category'               => ['nullable', 'string'],
            'sub_category_id'        => ['nullable'],
            'sub_category'           => ['nullable', 'string'],
            'version'                => ['nullable', 'string', 'max:50'],
            'demo_link'              => ['nullable', 'string', 'max:255'],
            'tags'                   => ['nullable'],
            'regular_price'          => ['nullable', 'numeric'],
            'regular_license_price'  => ['nullable', 'numeric'],
            'extended_price'         => ['nullable', 'numeric'],
            'extended_license_price' => ['nullable', 'numeric'],
            'is_supported'           => ['nullable'],
            'support'                => ['nullable'],
            'support_instructions'   => ['nullable', 'string', 'max:2000'],
            'is_free'                => ['nullable'],
            'free_item'              => ['nullable'],
            'purchasing_status'      => ['nullable'],
            'main_file_source'       => ['nullable'],
            'main_file_link'         => ['nullable', 'string', 'max:500'],
            'message'                => ['nullable', 'string', 'max:3000'],
            'thumbnail'              => ['nullable'],
            'preview_image'          => ['nullable'],
            'preview_video'          => ['nullable'],
            'preview_audio'          => ['nullable'],
            'main_file'              => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Resolve Category ID
        $categoryId = $request->category_id;
        if (!$categoryId && $request->filled('category')) {
            $catSlugOrName = $request->category;
            $cat = \App\Models\Category::where('slug', $catSlugOrName)
                ->orWhere('name', $catSlugOrName)
                ->orWhere('id', $catSlugOrName)
                ->first();
            $categoryId = $cat ? $cat->id : 21;
        }
        if (!$categoryId) {
            $categoryId = 21; // Default to Afrobeats
        }

        // Resolve SubCategory ID
        $subCategoryId = $request->sub_category_id;
        if (!$subCategoryId && $request->filled('sub_category')) {
            $subSlugOrName = $request->sub_category;
            $subCat = \App\Models\SubCategory::where('slug', $subSlugOrName)
                ->orWhere('name', $subSlugOrName)
                ->orWhere('id', $subSlugOrName)
                ->first();
            $subCategoryId = $subCat ? $subCat->id : null;
        }

        // Pricing
        $regularPrice = $request->regular_license_price ?? $request->regular_price ?? 29.99;
        $extendedPrice = $request->extended_license_price ?? $request->extended_price ?? ($regularPrice * 2);

        // Tags
        $tags = $request->tags;
        if (is_array($tags)) {
            $tags = implode(', ', $tags);
        }

        $item = new Item();
        $item->author_id = $author->id;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->category_id = $categoryId;
        $item->sub_category_id = $subCategoryId;
        $item->version = $request->version ?? '1.0';
        $item->demo_link = $request->demo_link;
        $item->tags = $tags;
        $item->regular_price = (float) $regularPrice;
        $item->extended_price = (float) $extendedPrice;
        $item->is_supported = (bool) ($request->support ?? $request->is_supported ?? false);
        $item->support_instructions = $item->is_supported ? $request->support_instructions : null;
        $item->is_free = (bool) ($request->free_item ?? $request->is_free ?? false);
        $item->purchasing_status = $request->filled('purchasing_status') ? (int) $request->purchasing_status : 1;
        $item->status = Item::STATUS_PENDING; // Sent for reviewer approval
        $item->preview_type = Item::PREVIEW_FILE_TYPE_AUDIO;

        // Handle File Uploads if present
        if ($request->hasFile('preview_audio')) {
            $audioPath = $request->file('preview_audio')->store('previews/audio', 'public');
            $item->preview_audio = 'storage/' . $audioPath;
        }

        if ($request->hasFile('preview_video')) {
            $videoPath = $request->file('preview_video')->store('previews/video', 'public');
            $item->preview_video = 'storage/' . $videoPath;
            $item->preview_type = Item::PREVIEW_FILE_TYPE_VIDEO;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $item->thumbnail = 'storage/' . $thumbPath;
        }

        if ($request->hasFile('preview_image')) {
            $previewImagePath = $request->file('preview_image')->store('previews/images', 'public');
            $item->preview_image = 'storage/' . $previewImagePath;
        }

        if ($request->hasFile('main_file')) {
            $mainFilePath = $request->file('main_file')->store('items/main', 'public');
            $item->main_file = 'storage/' . $mainFilePath;
            $item->is_main_file_external = 0;
        } elseif ($request->filled('main_file_link')) {
            $item->main_file = $request->main_file_link;
            $item->is_main_file_external = 1;
        }

        $item->save();

        // Create initial ItemHistory if model exists
        if (class_exists('\\App\\Models\\ItemHistory')) {
            try {
                $history = new \App\Models\ItemHistory();
                $history->item_id = $item->id;
                $history->author_id = $author->id;
                $history->title = \App\Models\ItemHistory::TITLE_SUBMISSION ?? 'Submission';
                $history->body = $request->message ?? 'Submitted for review from Mobile Studio';
                $history->save();
            } catch (\Throwable $th) {}
        }

        return response()->json([
            'success' => true,
            'message' => 'Beat uploaded successfully and submitted for review.',
            'item'    => [
                'id'            => $item->id,
                'name'          => $item->name,
                'slug'          => $item->slug,
                'status'        => $item->status,
                'status_name'   => $item->getStatusName(),
                'category_id'   => $item->category_id,
                'regular_price' => (float) $item->regular_price,
                'extended_price'=> (float) $item->extended_price,
            ],
        ], 201);
    }

    /**
     * Delete an item.
     */
    public function deleteBeat(Request $request, $id)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $item = Item::where('author_id', $author->id)->where('id', $id)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        if ($item->total_sales > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete an item that already has purchase sales.',
            ], 400);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Beat deleted successfully.',
        ], 200);
    }
}

