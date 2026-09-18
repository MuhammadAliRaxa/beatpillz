<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\PurchaseResource;
use App\Models\Purchase;
use App\Models\Statement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Get user purchases / library.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
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
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
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
     * Get legal license certificate details for a purchased beat.
     */
    public function license(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $purchase = Purchase::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', Purchase::STATUS_ACTIVE)
            ->with(['item.author', 'user', 'author'])
            ->first();

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase certificate not found.',
            ], 404);
        }

        $item = $purchase->item;

        return response()->json([
            'success'     => true,
            'certificate' => [
                'title'              => 'License Certificate',
                'purchase_code'      => $purchase->code,
                'license_type'       => (int) $purchase->license_type,
                'license_name'       => $purchase->isLicenseTypeRegular() ? 'Regular License' : 'Extended License',
                'item'               => [
                    'id'        => $item->id,
                    'name'      => $item->name,
                    'slug'      => $item->slug,
                    'url'       => $item->getLink(),
                    'thumbnail' => $item->getThumbnail(),
                ],
                'licensor'           => [
                    'id'       => $purchase->author ? $purchase->author->id : null,
                    'name'     => $purchase->author ? $purchase->author->getName() : null,
                    'username' => $purchase->author ? $purchase->author->username : null,
                ],
                'licensee'           => [
                    'id'       => $user->id,
                    'name'     => $user->getName(),
                    'username' => $user->username,
                    'email'    => $user->email,
                ],
                'purchase_date'      => $purchase->created_at ? $purchase->created_at->toISOString() : null,
                'support_expiry_at'  => $purchase->support_expiry_at ? $purchase->support_expiry_at->toISOString() : null,
                'is_support_expired' => $purchase->isSupportExpired(),
                'web_certificate_url'=> route('workspace.purchases.license', $purchase->id),
                'legal_notice'       => 'This document certifies the legal purchase of the specified license. The licensee is granted permission to use the work in accordance with the license tier terms.',
            ],
        ], 200);
    }

    /**
     * Producer License Verification Tool (verify buyer purchase code).
     */
    public function verifyLicense(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $validator = Validator::make($request->all(), [
            'purchase_code' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $code = trim($request->input('purchase_code'));

        $purchase = Purchase::where('code', $code)
            ->where('author_id', $user->id)
            ->where('status', Purchase::STATUS_ACTIVE)
            ->with(['item', 'user'])
            ->first();

        if (!$purchase) {
            return response()->json([
                'success'  => false,
                'is_valid' => false,
                'message'  => 'Invalid purchase code or no matching item found in your catalog.',
            ], 404);
        }

        return response()->json([
            'success'      => true,
            'is_valid'     => true,
            'verification' => [
                'purchase_code'      => $purchase->code,
                'license_type'       => (int) $purchase->license_type,
                'license_name'       => $purchase->isLicenseTypeRegular() ? 'Regular License' : 'Extended License',
                'item'               => [
                    'id'   => $purchase->item->id,
                    'name' => $purchase->item->name,
                    'slug' => $purchase->item->slug,
                    'url'  => $purchase->item->getLink(),
                ],
                'buyer'              => [
                    'id'       => $purchase->user->id,
                    'name'     => $purchase->user->getName(),
                    'username' => $purchase->user->username,
                    'avatar'   => $purchase->user->avatar ? asset($purchase->user->avatar) : null,
                ],
                'purchase_date'      => $purchase->created_at ? $purchase->created_at->toISOString() : null,
                'support_expiry_at'  => $purchase->support_expiry_at ? $purchase->support_expiry_at->toISOString() : null,
                'is_support_expired' => $purchase->isSupportExpired(),
                'is_downloaded'      => (bool) $purchase->is_downloaded,
            ],
        ], 200);
    }

    /**
     * Get user financial statements / transactions.
     */
    public function statements(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
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
