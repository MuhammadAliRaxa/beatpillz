<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\RefundAccepted;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Refund;
use App\Models\RefundReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    /**
     * List user refund requests (as buyer or author).
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $refunds = Refund::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('author_id', $user->id);
        })
        ->with(['purchase.item', 'user', 'author'])
        ->orderBy('id', 'desc')
        ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $refunds->map(function ($r) use ($user) {
                return [
                    'id'          => $r->id,
                    'reason'      => $r->reason,
                    'status'      => (int) $r->status,
                    'is_buyer'    => $r->user_id == $user->id,
                    'purchase_id' => $r->purchase_id,
                    'item'        => $r->purchase && $r->purchase->item ? [
                        'id'   => $r->purchase->item->id,
                        'name' => $r->purchase->item->name,
                        'slug' => $r->purchase->item->slug,
                    ] : null,
                    'created_at'  => $r->created_at ? $r->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $refunds->currentPage(),
                'last_page'    => $refunds->lastPage(),
                'total'        => $refunds->total(),
            ],
        ], 200);
    }

    /**
     * Submit a refund request for a purchase.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => ['required', 'exists:purchases,id'],
            'reason'      => ['required', 'string', 'max:255'],
            'message'     => ['required', 'string', 'max:3000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $purchase = Purchase::where('id', $request->purchase_id)
            ->where('user_id', $user->id)
            ->where('status', Purchase::STATUS_ACTIVE)
            ->first();

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Active purchase not found.',
            ], 404);
        }

        $existingRefund = Refund::where('purchase_id', $purchase->id)->first();
        if ($existingRefund) {
            return response()->json([
                'success' => false,
                'message' => 'A refund request already exists for this purchase.',
            ], 400);
        }

        $refund = new Refund();
        $refund->user_id = $user->id;
        $refund->author_id = $purchase->author_id;
        $refund->purchase_id = $purchase->id;
        $refund->reason = $request->reason;
        $refund->status = 1; // Pending
        $refund->save();

        $reply = new RefundReply();
        $reply->refund_id = $refund->id;
        $reply->user_id = $user->id;
        $reply->body = $request->message;
        $reply->save();

        return response()->json([
            'success' => true,
            'message' => 'Refund request submitted to producer.',
            'refund'  => [
                'id'         => $refund->id,
                'status'     => $refund->status,
                'created_at' => $refund->created_at ? $refund->created_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * View refund conversation details.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $refund = Refund::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('author_id', $user->id);
            })
            ->with(['purchase.item', 'replies.user', 'replies.admin'])
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Refund request not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $refund->id,
                'reason'      => $refund->reason,
                'status'      => (int) $refund->status,
                'item'        => $refund->purchase && $refund->purchase->item ? [
                    'id'   => $refund->purchase->item->id,
                    'name' => $refund->purchase->item->name,
                ] : null,
                'replies'     => $refund->replies->map(function ($reply) {
                    return [
                        'id'         => $reply->id,
                        'body'       => $reply->body,
                        'sender'     => [
                            'name'     => $reply->admin ? $reply->admin->name : ($reply->user ? $reply->user->getName() : 'User'),
                            'is_admin' => $reply->admin !== null,
                        ],
                        'created_at' => $reply->created_at ? $reply->created_at->toISOString() : null,
                    ];
                }),
                'created_at'  => $refund->created_at ? $refund->created_at->toISOString() : null,
            ],
        ], 200);
    }

    /**
     * Reply in a refund discussion.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string', 'max:3000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        $refund = Refund::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('author_id', $user->id);
            })
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Refund not found.',
            ], 404);
        }

        $reply = new RefundReply();
        $reply->refund_id = $refund->id;
        $reply->user_id = $user->id;
        $reply->body = $request->message;
        $reply->save();

        return response()->json([
            'success' => true,
            'message' => 'Reply posted.',
        ], 201);
    }
}
