<?php

namespace App\Http\Controllers\Workspace\Tools;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Validator;

class LicenseVerificationController extends Controller
{
    public function index()
    {
        return theme_view('workspace.tools.license-verification');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $purchase = Purchase::where('author_id', authUser()->id)
            ->where('code', $request->purchase_code)
            ->active()
            ->with('item')
            ->first();

        if (!$purchase) {
            toastr()->error(translate('Invalid purchase code'));
            return back();
        }

        return back()->with('purchase', $purchase)
            ->withInput();
    }
}
