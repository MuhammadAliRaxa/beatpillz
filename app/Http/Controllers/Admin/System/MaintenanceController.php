<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('admin.system.maintenance');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maintenance.icon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg'],
            'maintenance.title' => ['required_if:maintenance.status,on', 'nullable', 'string', 'max:150'],
            'maintenance.body' => ['required_if:maintenance.status,on', 'nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');
        $maintenance = $requestData['maintenance'];

        if ($request->has('maintenance.icon')) {
            $icon = imageUpload($request->file('maintenance.icon'), 'images/maintenance/', null, null, @settings('maintenance')->icon);
            $maintenance['icon'] = $icon;
        } else {
            $maintenance['icon'] = @settings('maintenance')->icon;
        }

        $maintenance['status'] = ($request->has('maintenance.status')) ? 1 : 0;

        $update = Settings::updateSettings('maintenance', $maintenance);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }
}
