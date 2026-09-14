<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\BadgeResource;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    /**
     * Get authenticated user's earned badges (ordered by sort_id).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $userBadges = UserBadge::where('user_id', $user->id)
            ->with('badge')
            ->get();

        return response()->json([
            'success' => true,
            'count'   => $userBadges->count(),
            'badges'  => BadgeResource::collection($userBadges),
        ], 200);
    }

    /**
     * Reorder / sort authenticated user's badges (same logic as web workspace).
     */
    public function sortable(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sort badges. The "ids" parameter is required (array or comma-separated list).',
            ], 422);
        }

        $userBadges = $user->badges()->get();
        foreach ($ids as $sortOrder => $id) {
            $userBadge = $userBadges->where('id', $id)->first();
            if ($userBadge) {
                $userBadge->sort_id = ($sortOrder + 1);
                $userBadge->update();
            }
        }

        $updatedBadges = UserBadge::where('user_id', $user->id)
            ->with('badge')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Badges sorted successfully.',
            'badges'  => BadgeResource::collection($updatedBadges),
        ], 200);
    }

    /**
     * Get public badges for any producer / user by username or ID.
     */
    public function userBadges(Request $request, $usernameOrId)
    {
        $targetUser = User::where('id', $usernameOrId)
            ->orWhere('username', $usernameOrId)
            ->first();

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $userBadges = UserBadge::where('user_id', $targetUser->id)
            ->with('badge')
            ->get();

        return response()->json([
            'success' => true,
            'count'   => $userBadges->count(),
            'badges'  => BadgeResource::collection($userBadges),
        ], 200);
    }
}
