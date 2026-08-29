<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ItemResource;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\ItemCommentReply;
use App\Models\ItemReview;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Browse / Search / Filter beats catalog.
     */
    public function index(Request $request)
    {
        $query = Item::where('status', Item::STATUS_APPROVED)
            ->with(['author', 'category', 'subCategory', 'discount']);

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('tags', 'like', "%{$keyword}%");
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $cat = $request->input('category');
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('slug', $cat)->orWhere('id', $cat);
            });
        }

        // Filter by SubCategory
        if ($request->filled('subcategory')) {
            $subCat = $request->input('subcategory');
            $query->whereHas('subCategory', function ($q) use ($subCat) {
                $q->where('slug', $subCat)->orWhere('id', $subCat);
            });
        }

        // Filter by Producer / Author
        if ($request->filled('author')) {
            $author = $request->input('author');
            $query->whereHas('author', function ($q) use ($author) {
                $q->where('username', $author)->orWhere('id', $author);
            });
        }

        // Filter by Free items
        if ($request->boolean('is_free')) {
            $query->where('is_free', 1);
        }

        // Filter by Premium items
        if ($request->boolean('is_premium')) {
            $query->where('is_premium', 1);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('regular_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('regular_price', '<=', (float) $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'popular':
            case 'best_selling':
                $query->orderBy('total_sales', 'desc');
                break;
            case 'rating':
                $query->orderBy('avg_reviews', 'desc');
                break;
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $perPage = min((int) $request->input('per_page', 15), 50);
        $items = $query->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => ItemResource::collection($items),
            'meta'         => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ], 200);
    }

    /**
     * Get single beat details with related beats.
     */
    public function show($slugOrId)
    {
        $item = Item::where('status', Item::STATUS_APPROVED)
            ->where(function ($q) use ($slugOrId) {
                $q->where('slug', $slugOrId)->orWhere('id', $slugOrId);
            })
            ->with(['author', 'category', 'subCategory', 'discount', 'changelogs'])
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        // Related beats in same category
        $relatedItems = Item::where('status', Item::STATUS_APPROVED)
            ->where('id', '!=', $item->id)
            ->where('category_id', $item->category_id)
            ->with(['author', 'discount'])
            ->latest()
            ->take(6)
            ->get();

        // More beats by same producer
        $authorItems = Item::where('status', Item::STATUS_APPROVED)
            ->where('id', '!=', $item->id)
            ->where('author_id', $item->author_id)
            ->with(['discount'])
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'item'    => new ItemResource($item),
            'related' => ItemResource::collection($relatedItems),
            'author_more' => ItemResource::collection($authorItems),
        ], 200);
    }

    /**
     * Get reviews for a beat.
     */
    public function reviews($id)
    {
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);
        $reviews = $item->reviews()->with('user')->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'reviews' => $reviews->map(function ($rev) {
                return [
                    'id'         => $rev->id,
                    'rating'     => (int) $rev->rating,
                    'subject'    => $rev->subject,
                    'body'       => $rev->body,
                    'user'       => [
                        'id'       => $rev->user->id,
                        'name'     => $rev->user->getName(),
                        'username' => $rev->user->username,
                        'avatar'   => $rev->user->avatar ? asset($rev->user->avatar) : null,
                    ],
                    'created_at' => $rev->created_at ? $rev->created_at->toISOString() : null,
                ];
            }),
        ], 200);
    }

    /**
     * Submit a rating and review for a purchased beat.
     */
    public function storeReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating'  => ['required', 'integer', 'between:1,5'],
            'subject' => ['required', 'string', 'max:150'],
            'body'    => ['required', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);

        if (!$user->hasPurchasedItem($item->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You must purchase this beat before reviewing it.',
            ], 403);
        }

        $review = ItemReview::where('user_id', $user->id)->where('item_id', $item->id)->first();
        if (!$review) {
            $review = new ItemReview();
            $review->user_id = $user->id;
            $review->author_id = $item->author_id;
            $review->item_id = $item->id;
        }

        $review->rating = $request->rating;
        $review->subject = $request->subject;
        $review->body = $request->body;
        $review->save();

        // Recalculate item avg reviews
        $item->avg_reviews = $item->reviews()->avg('rating') ?: 0;
        $item->total_reviews = $item->reviews()->count();
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'review'  => [
                'id'         => $review->id,
                'rating'     => (int) $review->rating,
                'subject'    => $review->subject,
                'body'       => $review->body,
                'created_at' => $review->created_at ? $review->created_at->toISOString() : null,
            ],
        ], 200);
    }

    /**
     * Download free beat directly without checkout.
     */
    public function downloadFree(Request $request, $id)
    {
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);

        if (!$item->isFree()) {
            return response()->json([
                'success' => false,
                'message' => 'This beat is not available for free download.',
            ], 400);
        }

        try {
            return $item->download();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get comments for a beat.
     */
    public function comments($id)
    {
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);
        $comments = $item->itemComments()->with(['user', 'replies.user'])->latest()->paginate(15);

        return response()->json([
            'success'  => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id'         => $comment->id,
                    'body'       => $comment->body,
                    'user'       => [
                        'id'       => $comment->user->id,
                        'name'     => $comment->user->getName(),
                        'username' => $comment->user->username,
                        'avatar'   => $comment->user->avatar ? asset($comment->user->avatar) : null,
                    ],
                    'replies'    => $comment->replies->map(function ($reply) {
                        return [
                            'id'         => $reply->id,
                            'body'       => $reply->body,
                            'user'       => [
                                'id'       => $reply->user->id,
                                'name'     => $reply->user->getName(),
                                'username' => $reply->user->username,
                                'avatar'   => $reply->user->avatar ? asset($reply->user->avatar) : null,
                            ],
                            'created_at' => $reply->created_at ? $reply->created_at->toISOString() : null,
                        ];
                    }),
                    'created_at' => $comment->created_at ? $comment->created_at->toISOString() : null,
                ];
            }),
        ], 200);
    }

    /**
     * Post a comment on a beat.
     */
    public function storeComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);

        $comment = new ItemComment();
        $comment->user_id = $request->user()->id;
        $comment->item_id = $item->id;
        $comment->body = $request->body;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully.',
            'comment' => [
                'id'         => $comment->id,
                'body'       => $comment->body,
                'created_at' => $comment->created_at ? $comment->created_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * Toggle favorite / wishlist status for a beat.
     */
    public function toggleFavorite(Request $request, $id)
    {
        $user = $request->user();
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($id);

        $favorite = Favorite::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success'      => true,
                'is_favorited' => false,
                'message'      => 'Removed from wishlist.',
            ], 200);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            return response()->json([
                'success'      => true,
                'is_favorited' => true,
                'message'      => 'Added to wishlist.',
            ], 200);
        }
    }

    /**
     * Get user's favorites / wishlist.
     */
    public function favorites(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favorites()->with('item.author', 'item.discount')->latest()->paginate(15);

        $items = $favorites->getCollection()->map(function ($fav) {
            return $fav->item ? new ItemResource($fav->item) : null;
        })->filter();

        return response()->json([
            'success' => true,
            'data'    => $items->values(),
        ], 200);
    }
}
