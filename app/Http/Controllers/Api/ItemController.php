<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function all(Request $request)
    {
        $validator = $this->validateApiKey($request);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $author = $this->getAuthor($request->api_key);

        if ($author) {
            $items = ItemResource::collection($author->items);
            if ($items->count() > 1) {
                return response()->json([
                    'status' => translate('success'),
                    'items' => $items,
                ], 200);
            }
        }

        return response()->json([
            'status' => translate('error'),
            'msg' => translate('No Items Found'),
        ], 404);
    }

    public function item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => ['required', 'string'],
            'item_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $author = $this->getAuthor($request->api_key);

        if ($author) {
            $item = $author->items()->approved()->find($request->item_id);
            if ($item) {
                return response()->json([
                    'status' => translate('success'),
                    'item' => new ItemResource($item),
                ], 200);
            }
        }

        return response()->json([
            'status' => translate('error'),
            'msg' => translate('Item Not Found'),
        ], 404);
    }

    private function validateApiKey(Request $request)
    {
        return Validator::make($request->all(), [
            'api_key' => ['required', 'string'],
        ]);
    }

    private function validationErrorResponse($validator)
    {
        return response()->json([
            'status' => translate('error'),
            'msg' => $validator->errors()->first(),
        ], 400);
    }

    private function getAuthor($apiKey)
    {
        return User::where('api_key', $apiKey)
            ->author()->with(['items' => function ($query) {
            return $query->orderbyDesc('id')->approved();
        }])->first();
    }
}