<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\PurchaseResource;
use App\Models\Purchase;
use App\Models\Statement;
use Exception;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Get user purchases / library.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Purchase::where('user_id', $user->id)
            ->where('status', Purchase::STATUS_ACTIVE)
            ->with(['item.author', 'item.category', 'item.discount']);

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', "%{$keyword}%")
                  ->orWhereHas('item', function ($iq) use ($keyword) {
                      $iq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        $purchases = $query->orderBy('id', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => PurchaseResource::collection($purchases),
            'meta'    => [
                'current_page' => $purchases->currentPage(),
                'last_page'    => $purchases->lastPage(),
                'per_page'     => $purchases->perPage(),
                'total'        => $purchases->total(),
            ],
        ], 200);
    }

    /**
     * Download main files for a purchased beat.
     */
    public function download(Request $request, $id)
    {
        $user = $request->user();
        $purchase = Purchase::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', Purchase::STATUS_ACTIVE)
            ->first();

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found or not active.',
            ], 404);
        }

        $item = $purchase->item;
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Associated item not found.',
            ], 404);
        }

        try {
            $purchase->is_downloaded = Purchase::DOWNLOADED;
            $purchase->save();

            return $item->download();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user financial statements / transactions.
     */
    public function statements(Request $request)
    {
        $user = $request->user();
        $statements = Statement::where('user_id', $user->id)
            ->with('item')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $statements->map(function ($stmt) {
                return [
                    'id'          => $stmt->id,
                    'title'       => $stmt->title,
                    'amount'      => (float) $stmt->amount,
                    'total'       => (float) $stmt->total,
                    'type'        => $stmt->type,
                    'item'        => $stmt->item ? [
                        'id'   => $stmt->item->id,
                        'name' => $stmt->item->name,
                        'slug' => $stmt->item->slug,
                    ] : null,
                    'created_at'  => $stmt->created_at ? $stmt->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $statements->currentPage(),
                'last_page'    => $statements->lastPage(),
                'per_page'     => $statements->perPage(),
                'total'        => $statements->total(),
            ],
        ], 200);
    }
}
