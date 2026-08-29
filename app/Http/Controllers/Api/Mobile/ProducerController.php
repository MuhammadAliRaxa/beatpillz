<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ItemResource;
use App\Http\Resources\Mobile\UserResource;
use App\Models\Follower;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    /**
     * Get public producer / author profile with their beats.
     */
    public function show(Request $request, $usernameOrId)
    {
        $author = User::where('status', User::STATUS_ACTIVE)
            ->where(function ($q) use ($usernameOrId) {
                $q->where('username', $usernameOrId)->orWhere('id', $usernameOrId);
            })
            ->first();

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found.',
            ], 404);
        }

        $items = Item::where('author_id', $author->id)
            ->where('status', Item::STATUS_APPROVED)
            ->with(['category', 'discount'])
            ->latest()
            ->paginate(15);

        $isFollowing = false;
        if (auth('sanctum')->check()) {
            $isFollowing = auth('sanctum')->user()->isFollowingUser($author->id);
        }

        return response()->json([
            'success'      => true,
            'producer'     => new UserResource($author),
            'is_following' => (bool) $isFollowing,
            'beats'        => ItemResource::collection($items),
            'meta'         => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ], 200);
    }

    /**
     * Follow or Unfollow a producer.
     */
    public function toggleFollow(Request $request, $id)
    {
        $user = $request->user();

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
            return response()->json([
                'success'      => true,
                'is_following' => true,
                'message'      => 'Following producer.',
            ], 200);
        }
    }

    /**
     * List producers followed by the user.
     */
    public function following(Request $request)
    {
        $user = $request->user();
        $following = $user->followings()->with('following')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $following->map(function ($f) {
                return $f->following ? new UserResource($f->following) : null;
            })->filter()->values(),
        ], 200);
    }
}
