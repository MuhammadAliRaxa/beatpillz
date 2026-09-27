<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.settings.profile');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile.default_avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
            'profile.default_cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');

        if ($request->has('profile.default_avatar')) {
            $defaultAvatar = imageUpload($request->file('profile.default_avatar'), 'images/profiles/default/', '120x120', null, @settings('profile')->default_avatar);
            $requestData['profile']['default_avatar'] = $defaultAvatar;
        } else {
            $requestData['profile']['default_avatar'] = @settings('profile')->default_avatar;
        }

        if ($request->has('profile.default_cover')) {
            $defaultCover = imageUpload($request->file('profile.default_cover'), 'images/profiles/default/', '1200x500', null, @settings('profile')->default_cover);
            $requestData['profile']['default_cover'] = $defaultCover;
        } else {
            $requestData['profile']['default_cover'] = @settings('profile')->default_cover;
        }

        $update = Settings::updateSettings('profile', $requestData['profile']);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();

    }
}
