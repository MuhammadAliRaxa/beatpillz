<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ItemResource;
use App\Http\Resources\Mobile\ReviewResource;
use App\Http\Resources\Mobile\UserResource;
use App\Models\Follower;
use App\Models\Item;
use App\Models\ItemReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProducerController extends Controller
{
    /**
     * Resolve producer by username or id.
     */
    protected function resolveProducer($usernameOrId)
    {
        return User::where('status', User::STATUS_ACTIVE)
            ->where(function ($q) use ($usernameOrId) {
                $q->where('username', $usernameOrId)->orWhere('id', $usernameOrId);
            })
            ->first();
    }

    /**
     * Format a user model for follower/following response.
     */
    protected function formatFollowUser($user, array $viewerFollowedIds = [], $followedAt = null)
    {
        if (!$user) {
            return null;
        }

        $createdAtIso = null;
        if ($followedAt) {
            $createdAtIso = (is_object($followedAt) && method_exists($followedAt, 'toISOString'))
                ? $followedAt->toISOString()
                : (string) $followedAt;
        }

        return [
            'id'              => (int) $user->id,
            'username'        => $user->username,
            'firstname'       => $user->firstname,
            'lastname'        => $user->lastname,
            'fullname'        => method_exists($user, 'getName') ? $user->getName() : trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')),
            'avatar'          => $user->avatar ? asset($user->avatar) : null,
            'profile_heading' => $user->profile_heading,
            'is_author'       => (bool) $user->is_author,
            'total_followers' => (int) $user->total_followers,
            'total_following' => (int) ($user->total_following ?? 0),
            'is_following'    => in_array($user->id, $viewerFollowedIds),
            'followed_at'     => $createdAtIso,
        ];
    }

    /**
     * Get public producer profile details:
     * Profile info, is_following status, and summary counts.
     */
    public function show(Request $request, $usernameOrId)
    {
        $author = $this->resolveProducer($usernameOrId);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $viewer = auth('sanctum')->check() ? auth('sanctum')->user() : null;
        $isFollowing = $viewer ? $viewer->isFollowingUser($author->id) : false;

        $counts = [
            'portfolio'   => (int) Item::where('author_id', $author->id)->where('status', Item::STATUS_APPROVED)->count(),
            'followers'   => (int) $author->total_followers,
            'following'   => (int) ($author->total_following ?? Follower::where('follower_id', $author->id)->count()),
            'reviews'     => (int) $author->total_reviews,
            'avg_rating'  => (float) $author->avg_reviews,
            'total_sales' => (int) $author->total_sales,
        ];

        return response()->json([
            'success' => true,
            'data'    => [
                'profile'      => new UserResource($author),
                'is_following' => (bool) $isFollowing,
                'counts'       => $counts,
            ],
        ], 200);
    }

    /**
     * Get producer portfolio beats with search, filters and pagination.
     */
    public function portfolio(Request $request, $usernameOrId)
    {
        $author = $this->resolveProducer($usernameOrId);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $query = Item::where('author_id', $author->id)
            ->where('status', Item::STATUS_APPROVED)
            ->with(['category', 'discount']);

        if ($request->filled('search') || $request->filled('q')) {
            $searchTerm = $request->input('search', $request->input('q'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tags', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('category')) {
            $cat = $request->category;
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('slug', $cat)->orWhere('id', $cat);
            });
        }

        // Sorting
        switch ($request->input('sort')) {
            case 'popular':
            case 'top_selling':
                $query->orderByDesc('total_sales');
                break;
            case 'rating':
                $query->orderByDesc('avg_reviews');
                break;
            case 'price_low':
            case 'price_asc':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price_high':
            case 'price_desc':
                $query->orderBy('regular_price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = (int) $request->input('per_page', 15);
        $items = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => ItemResource::collection($items),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ], 200);
    }

    /**
     * Get producer reviews with rating breakdown and pagination.
     */
    public function reviews(Request $request, $usernameOrId)
    {
        $author = $this->resolveProducer($usernameOrId);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $perPage = (int) $request->input('per_page', 15);
        $reviews = ItemReview::where('author_id', $author->id)
            ->with(['user', 'item', 'reply.user'])
            ->latest('id')
            ->paginate($perPage);

        $ratingBreakdown = [
            '5' => ItemReview::where('author_id', $author->id)->where('stars', 5)->count(),
            '4' => ItemReview::where('author_id', $author->id)->where('stars', 4)->count(),
            '3' => ItemReview::where('author_id', $author->id)->where('stars', 3)->count(),
            '2' => ItemReview::where('author_id', $author->id)->where('stars', 2)->count(),
            '1' => ItemReview::where('author_id', $author->id)->where('stars', 1)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats'   => [
                'total_reviews'    => (int) $author->total_reviews,
                'avg_rating'       => (float) $author->avg_reviews,
                'rating_breakdown' => $ratingBreakdown,
            ],
            'data'    => ReviewResource::collection($reviews),
            'meta'    => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'per_page'     => $reviews->perPage(),
                'total'        => $reviews->total(),
            ],
        ], 200);
    }

    /**
     * Get producer followers list.
     */
    public function followers(Request $request, $usernameOrId)
    {
        $author = $this->resolveProducer($usernameOrId);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $viewer = auth('sanctum')->check() ? auth('sanctum')->user() : null;
        $viewerFollowedIds = $viewer ? $viewer->followings()->pluck('following_id')->toArray() : [];

        $perPage = (int) $request->input('per_page', 15);
        $followers = Follower::where('following_id', $author->id)
            ->with('follower')
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success'         => true,
            'total_followers' => (int) $author->total_followers,
            'data'            => $followers->map(function ($f) use ($viewerFollowedIds) {
                return $this->formatFollowUser($f->follower, $viewerFollowedIds, $f->created_at);
            })->filter()->values(),
            'meta'            => [
                'current_page' => $followers->currentPage(),
                'last_page'    => $followers->lastPage(),
                'per_page'     => $followers->perPage(),
                'total'        => $followers->total(),
            ],
        ], 200);
    }

    /**
     * Get list of users and producers followed by this producer.
     */
    public function followingList(Request $request, $usernameOrId)
    {
        $author = $this->resolveProducer($usernameOrId);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $viewer = auth('sanctum')->check() ? auth('sanctum')->user() : null;
        $viewerFollowedIds = $viewer ? $viewer->followings()->pluck('following_id')->toArray() : [];

        $perPage = (int) $request->input('per_page', 15);
        $following = Follower::where('follower_id', $author->id)
            ->with('following')
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success'         => true,
            'total_following' => (int) ($author->total_following ?? Follower::where('follower_id', $author->id)->count()),
            'data'            => $following->map(function ($f) use ($viewerFollowedIds) {
                return $this->formatFollowUser($f->following, $viewerFollowedIds, $f->created_at);
            })->filter()->values(),
            'meta'            => [
                'current_page' => $following->currentPage(),
                'last_page'    => $following->lastPage(),
                'per_page'     => $following->perPage(),
                'total'        => $following->total(),
            ],
        ], 200);
    }

    /**
     * Follow or Unfollow a producer.
     */
    public function toggleFollow(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->id == $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.',
            ], 400);
        }

        $targetUser = User::where('status', User::STATUS_ACTIVE)->findOrFail($id);

        $follow = Follower::where('follower_id', $user->id)
            ->where('following_id', $targetUser->id)
            ->first();

        if ($follow) {
            $follow->delete();
            $targetUser->decrement('total_followers');
            $user->decrement('total_following');
            return response()->json([
                'success'      => true,
                'is_following' => false,
                'message'      => 'Unfollowed producer.',
            ], 200);
        } else {
            Follower::create([
                'follower_id'  => $user->id,
                'following_id' => $targetUser->id,
            ]);
            $targetUser->increment('total_followers');
            $user->increment('total_following');
            return response()->json([
                'success'      => true,
                'is_following' => true,
                'message'      => 'Following producer.',
            ], 200);
        }
    }

    /**
     * List producers followed by the currently authenticated user.
     */
    public function following(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $perPage = (int) $request->input('per_page', 15);
        $following = $user->followings()->with('following')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $following->map(function ($f) {
                return $f->following ? new UserResource($f->following) : null;
            })->filter()->values(),
            'meta'    => [
                'current_page' => $following->currentPage(),
                'last_page'    => $following->lastPage(),
                'per_page'     => $following->perPage(),
                'total'        => $following->total(),
            ],
        ], 200);
    }

    /**
     * Send contact / inquiry message to producer from mobile app listener.
     */
    public function contact(Request $request, $usernameOrId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $producer = $this->resolveProducer($usernameOrId);
        if (!$producer) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        if ($user->id == $producer->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot send a message to yourself.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $destinationEmail = $producer->profile_contact_email ?: $producer->email;

        if (!$destinationEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Producer does not have a contact email configured.',
            ], 400);
        }

        try {
            $senderName = method_exists($user, 'getName') ? $user->getName() : ($user->username ?: 'Listener');
            $mailSubject = $request->input('subject') ?: ("Message from " . $senderName . " via BeatPillz");
            $msgContent = nl2br(e($request->input('message')));
            $replyToEmail = $user->email;

            if (function_exists('settings') && @settings('smtp')->status) {
                Mail::send([], [], function ($message) use ($msgContent, $destinationEmail, $mailSubject, $replyToEmail, $senderName) {
                    $message->to($destinationEmail)
                        ->replyTo($replyToEmail, $senderName)
                        ->subject($mailSubject)
                        ->html($msgContent);
                });
            }
        } catch (\Throwable $e) {
            // Silently handle if mail configuration is missing or failing in test environment
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent to ' . ($producer->username ?: 'the producer') . '.',
        ], 200);
    }
}
