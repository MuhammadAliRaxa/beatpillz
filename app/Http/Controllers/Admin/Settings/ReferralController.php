<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class ReferralController extends Controller
{
    public function index()
    {
        return view('admin.settings.referral');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral.percentage' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');
        $requestData['referral']['status'] = ($request->has('referral.status')) ? 1 : 0;

        $update = Settings::updateSettings('referral', $requestData['referral']);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }
}
