<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\CartResource;
use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's active cart.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['item.author', 'item.category', 'item.discount'])
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->getTotalAmount();
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'items'       => CartResource::collection($cartItems),
                'items_count' => $cartItems->count(),
                'subtotal'    => (float) $subtotal,
                'currency'    => function_exists('defaultCurrency') ? @defaultCurrency()->code : 'USD',
            ],
        ], 200);
    }

    /**
     * Add beat to cart.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id'      => ['required', 'exists:items,id'],
            'license_type' => ['required', 'in:1,2'], // 1: Regular, 2: Extended
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        $item = Item::where('status', Item::STATUS_APPROVED)->findOrFail($request->item_id);

        if ($item->author_id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot purchase your own item.',
            ], 400);
        }

        $existing = CartItem::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if ($existing) {
            $existing->license_type = $request->license_type;
            $existing->save();
        } else {
            CartItem::create([
                'user_id'      => $user->id,
                'item_id'      => $item->id,
                'license_type' => $request->license_type,
                'quantity'     => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart.',
        ], 200);
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, $id)
    {
        $user = $request->user();
        $cartItem = CartItem::where('user_id', $user->id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
        ], 200);
    }

    /**
     * Clear entire cart.
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        CartItem::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
        ], 200);
    }
}
