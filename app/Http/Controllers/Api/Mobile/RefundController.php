<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\RefundAccepted;
use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorNewRefundNotification;
use App\Jobs\SendRefundDeclinedNotification;
use App\Jobs\SendRefundReplyNotification;
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
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $refunds = Refund::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('author_id', $user->id);
        });

        if ($request->filled('status')) {
            $refunds->where('status', $request->status);
        }

        $refunds = $refunds->with(['purchase.item', 'user', 'author', 'replies'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $refunds->map(function ($r) use ($user) {
                $firstReply = $r->replies->first();
                return [
                    'id'          => $r->id,
                    'reason'      => $firstReply ? $firstReply->body : '',
                    'status'      => (int) $r->status,
                    'status_name' => $r->getStatusName(),
                    'is_buyer'    => $r->user_id == $user->id,
                    'is_author'   => $r->author_id == $user->id,
                    'purchase_id' => $r->purchase_id,
                    'item'        => $r->purchase && $r->purchase->item ? [
                        'id'        => $r->purchase->item->id,
                        'name'      => $r->purchase->item->name,
                        'slug'      => $r->purchase->item->slug,
                        'thumbnail' => $r->purchase->item->getThumbnail(),
                        'price'     => (float) $r->purchase->item->price->regular,
                    ] : null,
                    'buyer'       => $r->user ? [
                        'id'       => $r->user->id,
                        'username' => $r->user->username,
                        'avatar'   => $r->user->getAvatar(),
                    ] : null,
                    'author'      => $r->author ? [
                        'id'       => $r->author->id,
                        'username' => $r->author->username,
                        'avatar'   => $r->author->getAvatar(),
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
     * Submit a refund request for a purchase (Buyer).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => ['nullable', 'integer'],
            'purchase'    => ['nullable', 'integer'],
            'reason'      => ['nullable', 'string', 'max:5000'],
            'message'     => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $purchaseId = $request->input('purchase_id') ?? $request->input('purchase');
        $reasonBody = $request->input('reason') ?? $request->input('message');

        if (empty($purchaseId)) {
            return response()->json(['success' => false, 'message' => 'The purchase field is required.'], 422);
        }
        if (empty($reasonBody)) {
            return response()->json(['success' => false, 'message' => 'The refund reason is required.'], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $purchase = Purchase::where('id', $purchaseId)
            ->where('user_id', $user->id)
            ->active()
            ->first();

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Active purchase not found for this account.',
            ], 404);
        }

        $existingPending = Refund::where('purchase_id', $purchase->id)->pending()->first();
        if ($existingPending) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending refund request for that item.',
            ], 400);
        }

        $item = $purchase->item;
        $author = $item ? $item->author : null;

        $refund = new Refund();
        $refund->user_id = $user->id;
        $refund->author_id = $author ? $author->id : $purchase->author_id;
        $refund->purchase_id = $purchase->id;
        $refund->status = Refund::STATUS_PENDING;
        $refund->save();

        $refundReply = new RefundReply();
        $refundReply->refund_id = $refund->id;
        $refundReply->user_id = $user->id;
        $refundReply->body = $reasonBody;
        $refundReply->save();

        try {
            dispatch(new SendAuthorNewRefundNotification($refund, $refundReply));
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Your refund request has been submitted successfully.',
            'refund'  => [
                'id'          => $refund->id,
                'status'      => (int) $refund->status,
                'status_name' => $refund->getStatusName(),
                'created_at'  => $refund->created_at ? $refund->created_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * View refund conversation details.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $refund = Refund::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('author_id', $user->id);
            })
            ->with(['purchase.item', 'user', 'author', 'replies.user', 'replies.admin'])
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Refund request not found.',
            ], 404);
        }

        $firstReply = $refund->replies->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $refund->id,
                'reason'      => $firstReply ? $firstReply->body : '',
                'status'      => (int) $refund->status,
                'status_name' => $refund->getStatusName(),
                'is_buyer'    => $refund->user_id == $user->id,
                'is_author'   => $refund->author_id == $user->id,
                'item'        => $refund->purchase && $refund->purchase->item ? [
                    'id'        => $refund->purchase->item->id,
                    'name'      => $refund->purchase->item->name,
                    'slug'      => $refund->purchase->item->slug,
                    'thumbnail' => $refund->purchase->item->getThumbnail(),
                ] : null,
                'purchase'    => $refund->purchase ? [
                    'id'           => $refund->purchase->id,
                    'code'         => $refund->purchase->code,
                    'license_type' => $refund->purchase->license_type,
                    'status'       => $refund->purchase->status,
                ] : null,
                'buyer'       => $refund->user ? [
                    'id'       => $refund->user->id,
                    'username' => $refund->user->username,
                    'avatar'   => $refund->user->getAvatar(),
                ] : null,
                'author'      => $refund->author ? [
                    'id'       => $refund->author->id,
                    'username' => $refund->author->username,
                    'avatar'   => $refund->author->getAvatar(),
                ] : null,
                'replies'     => $refund->replies->map(function ($reply) {
                    return [
                        'id'         => $reply->id,
                        'body'       => $reply->body,
                        'sender'     => [
                            'id'       => $reply->admin ? $reply->admin->id : ($reply->user ? $reply->user->id : null),
                            'name'     => $reply->admin ? $reply->admin->name : ($reply->user ? $reply->user->getName() : 'User'),
                            'avatar'   => $reply->admin ? asset('images/avatars/admin.png') : ($reply->user ? $reply->user->getAvatar() : null),
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
     * Reply in a refund discussion (Buyer or Author).
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reply'   => ['nullable', 'string', 'max:5000'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $replyBody = $request->input('reply') ?? $request->input('message');
        if (empty($replyBody)) {
            return response()->json(['success' => false, 'message' => 'The reply message is required.'], 422);
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $refund = Refund::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('author_id', $user->id);
            })
            ->pending()
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Pending refund request not found.',
            ], 404);
        }

        $refundReply = new RefundReply();
        $refundReply->refund_id = $refund->id;
        $refundReply->user_id = $user->id;
        $refundReply->body = $replyBody;
        $refundReply->save();

        try {
            dispatch(new SendRefundReplyNotification($refundReply));
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Your reply has been sent successfully.',
            'reply'   => [
                'id'         => $refundReply->id,
                'body'       => $refundReply->body,
                'created_at' => $refundReply->created_at ? $refundReply->created_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * Author accepts refund request (credits buyer balance, debits author, cancels purchase license).
     */
    public function accept(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $refund = Refund::where('id', $id)
            ->where('author_id', $user->id)
            ->pending()
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Pending refund request not found.',
            ], 404);
        }

        $refund->status = Refund::STATUS_ACCEPTED;
        $refund->update();

        event(new RefundAccepted($refund));

        return response()->json([
            'success' => true,
            'message' => 'The refund request has been accepted and processed.',
            'refund'  => [
                'id'          => $refund->id,
                'status'      => (int) $refund->status,
                'status_name' => $refund->getStatusName(),
            ],
        ], 200);
    }

    /**
     * Author declines refund request with a reason.
     */
    public function decline(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'reason'  => ['nullable', 'string', 'max:5000'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $declineReason = $request->input('reason') ?? $request->input('message');
        if (empty($declineReason)) {
            return response()->json(['success' => false, 'message' => 'A decline explanation reason is required.'], 422);
        }

        $refund = Refund::where('id', $id)
            ->where('author_id', $user->id)
            ->pending()
            ->first();

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Pending refund request not found.',
            ], 404);
        }

        $refundReply = new RefundReply();
        $refundReply->refund_id = $refund->id;
        $refundReply->user_id = $user->id;
        $refundReply->body = $declineReason;
        $refundReply->save();

        $refund->status = Refund::STATUS_DECLINED;
        $refund->update();

        try {
            dispatch(new SendRefundDeclinedNotification($refund, $refundReply));
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'The refund request has been declined.',
            'refund'  => [
                'id'          => $refund->id,
                'status'      => (int) $refund->status,
                'status_name' => $refund->getStatusName(),
            ],
        ], 200);
    }
}
