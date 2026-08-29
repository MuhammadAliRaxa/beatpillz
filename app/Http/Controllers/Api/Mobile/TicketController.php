<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * List user support tickets.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tickets = Ticket::where('user_id', $user->id)
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $tickets->map(function ($ticket) {
                return [
                    'id'          => $ticket->id,
                    'subject'     => $ticket->subject,
                    'status'      => (int) $ticket->status,
                    'status_name' => $ticket->getStatusName(),
                    'category'    => $ticket->category ? [
                        'id'   => $ticket->category->id,
                        'name' => $ticket->category->name,
                    ] : null,
                    'created_at'  => $ticket->created_at ? $ticket->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
                'per_page'     => $tickets->perPage(),
                'total'        => $tickets->total(),
            ],
        ], 200);
    }

    /**
     * Get available ticket categories for creating a ticket.
     */
    public function categories()
    {
        $categories = TicketCategory::all();

        return response()->json([
            'success'    => true,
            'categories' => $categories->map(function ($c) {
                return [
                    'id'   => $c->id,
                    'name' => $c->name,
                ];
            }),
        ], 200);
    }

    /**
     * Create a new support ticket.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_category_id' => ['required', 'exists:ticket_categories,id'],
            'subject'            => ['required', 'string', 'max:255'],
            'message'            => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        $ticket = new Ticket();
        $ticket->user_id = $user->id;
        $ticket->ticket_category_id = $request->ticket_category_id;
        $ticket->subject = $request->subject;
        $ticket->status = Ticket::STATUS_OPENED;
        $ticket->save();

        $reply = new TicketReply();
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $user->id;
        $reply->body = $request->message;
        $reply->save();

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully.',
            'ticket'  => [
                'id'         => $ticket->id,
                'subject'    => $ticket->subject,
                'status'     => $ticket->status,
                'created_at' => $ticket->created_at ? $ticket->created_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * View ticket details and replies.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $ticket = Ticket::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['category', 'replies.user', 'replies.admin'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $ticket->id,
                'subject'     => $ticket->subject,
                'status'      => (int) $ticket->status,
                'status_name' => $ticket->getStatusName(),
                'category'    => $ticket->category ? $ticket->category->name : null,
                'replies'     => $ticket->replies->map(function ($reply) {
                    $sender = $reply->admin ? [
                        'name'     => $reply->admin->name,
                        'is_admin' => true,
                        'avatar'   => null,
                    ] : [
                        'name'     => $reply->user ? $reply->user->getName() : 'User',
                        'is_admin' => false,
                        'avatar'   => $reply->user && $reply->user->avatar ? asset($reply->user->avatar) : null,
                    ];

                    return [
                        'id'         => $reply->id,
                        'body'       => $reply->body,
                        'sender'     => $sender,
                        'created_at' => $reply->created_at ? $reply->created_at->toISOString() : null,
                    ];
                }),
                'created_at'  => $ticket->created_at ? $ticket->created_at->toISOString() : null,
            ],
        ], 200);
    }

    /**
     * Reply to an open ticket.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        $ticket = Ticket::where('id', $id)->where('user_id', $user->id)->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        if ($ticket->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket is closed.',
            ], 400);
        }

        $reply = new TicketReply();
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $user->id;
        $reply->body = $request->message;
        $reply->save();

        return response()->json([
            'success' => true,
            'message' => 'Reply submitted.',
            'reply'   => [
                'id'         => $reply->id,
                'body'       => $reply->body,
                'created_at' => $reply->created_at ? $reply->created_at->toISOString() : null,
            ],
        ], 201);
    }
}
