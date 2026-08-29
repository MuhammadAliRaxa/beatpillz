<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => translate('error'),
                'msg' => $validator->errors()->first(),
            ], 400);
        }

        $user = User::where('api_key', $request->api_key)
            ->active()->whereDataCompleted()->first();

        if (!$user) {
            return response()->json([
                'status' => translate('error'),
                'msg' => translate('Invalid request'),
            ], 401);
        }

        return response()->json([
            'status' => translate('success'),
            'data' => new AccountResource($user),
        ], 200);
    }
}
