<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class WatermarkController extends Controller
{
    public function index()
    {
        return view('admin.settings.watermark');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'watermark.image' => 'nullable|image|mimes:png',
            'watermark.position' => 'required|in:' . implode(',', array_keys(Settings::watermarkOptions())),
            'watermark.width' => 'required|integer|min:25|max:10000',
            'watermark.height' => 'required|integer|min:25|max:10000',
            'watermark.rotate' => 'required',
            'watermark.opacity' => 'required|integer|min:5|max:100',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');

        if ($request->has('watermark.image')) {
            $image = imageUpload($request->file('watermark.image'), 'images/watermark/', null, null, settings('watermark')->image);
            $requestData['watermark']['image'] = $image;
        } else {
            $requestData['watermark']['image'] = settings('watermark')->image;
        }

        $requestData['watermark']['status'] = $request->has('watermark.status') ? 1 : 0;

        $update = Settings::updateSettings('watermark', $requestData['watermark']);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

}
