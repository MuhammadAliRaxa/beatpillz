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
    public function uploadBeat(Request $request)
    {
        $author = $request->user();

        if (!$author->is_author) {
            return response()->json([
                'success' => false,
                'message' => 'Only registered authors can upload beats.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'             => ['required', 'string', 'max:150'],
            'category_id'      => ['required', 'exists:categories,id'],
            'sub_category_id'  => ['nullable', 'exists:sub_categories,id'],
            'description'      => ['required', 'string'],
            'regular_price'    => ['required', 'numeric', 'min:1'],
            'extended_price'   => ['nullable', 'numeric', 'min:1'],
            'tags'             => ['nullable', 'string', 'max:255'],
            'preview_audio'    => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a', 'max:30720'],
            'thumbnail'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = new Item();
        $item->author_id = $author->id;
        $item->name = $request->name;
        $item->category_id = $request->category_id;
        $item->sub_category_id = $request->sub_category_id;
        $item->description = $request->description;
        $item->regular_price = $request->regular_price;
        $item->extended_price = $request->extended_price ?? ($request->regular_price * 2);
        $item->tags = $request->tags;
        $item->status = Item::STATUS_PENDING; // Sent for reviewer review
        $item->preview_type = Item::PREVIEW_FILE_TYPE_AUDIO;

        if ($request->hasFile('preview_audio')) {
            $audioPath = $request->file('preview_audio')->store('previews/audio', 'public');
            $item->preview_audio = 'storage/' . $audioPath;
        }

        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $item->thumbnail = 'storage/' . $thumbPath;
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Beat uploaded successfully and submitted for review.',
            'item'    => [
                'id'            => $item->id,
                'name'          => $item->name,
                'slug'          => $item->slug,
                'status'        => $item->status,
                'status_name'   => $item->getStatusName(),
                'regular_price' => (float) $item->regular_price,
            ],
        ], 201);
    }

    /**
     * Delete an item.
     */
    public function deleteBeat(Request $request, $id)
    {
        $author = $request->user();
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

