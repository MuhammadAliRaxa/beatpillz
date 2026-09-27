<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    public function index()
    {
        return view('admin.settings.item');
    }

    public function update(Request $request)
    {
        $rules = [
            'item.maximum_tags' => ['required', 'integer', 'min:1', 'max:100'],
            'item.minimum_price' => ['required', 'numeric', 'min:0'],
            'item.maximum_price' => ['required', 'numeric', 'min:0'],
            'item.trending_number' => ['required', 'integer', 'min:1'],
            'item.best_selling_number' => ['required', 'integer', 'min:1'],
            'item.max_files' => ['required', 'integer', 'min:1'],
            'item.max_file_size' => ['required', 'integer', 'min:1'],
            'item.file_duration' => ['required', 'integer', 'min:1'],
            'item.convert_images_webp' => ['required', 'boolean'],
        ];

        if ($request->has('item.discount_status')) {
            $rules['item.discount_max_percentage'] = ['required', 'integer', 'min:1', 'max:90'];
            $rules['item.discount_max_days'] = ['required', 'integer', 'min:0', 'max:365'];
            $rules['item.discount_tb'] = ['required', 'integer', 'min:0', 'max:365'];
            $rules['item.discount_tb_pch'] = ['required', 'integer', 'min:0', 'max:365'];
        } else {
            $rules['item.discount_max_percentage'] = ['nullable', 'integer', 'min:1', 'max:90'];
            $rules['item.discount_max_days'] = ['nullable', 'integer', 'min:0', 'max:365'];
            $rules['item.discount_tb'] = ['nullable', 'integer', 'min:0', 'max:365'];
            $rules['item.discount_tb_pch'] = ['nullable', 'integer', 'min:0', 'max:365'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');
        $itemSettings = $requestData['item'];

        $itemSettings['max_file_size'] = ($itemSettings['max_file_size'] * 1048576);

        $itemSettings['buy_now_button'] = $request->has('item.buy_now_button') ? 1 : 0;
        $itemSettings['adding_require_review'] = $request->has('item.adding_require_review') ? 1 : 0;
        $itemSettings['updating_require_review'] = $request->has('item.updating_require_review') ? 1 : 0;
        $itemSettings['discount_status'] = ($request->has('item.discount_status')) ? 1 : 0;
        $itemSettings['free_item_option'] = ($request->has('item.free_item_option')) ? 1 : 0;
        $itemSettings['free_item_total_downloads'] = ($request->has('item.free_item_total_downloads')) ? 1 : 0;
        $itemSettings['free_items_require_login'] = ($request->has('item.free_items_require_login')) ? 1 : 0;
        $itemSettings['reviews_status'] = ($request->has('item.reviews_status')) ? 1 : 0;
        $itemSettings['comments_status'] = ($request->has('item.comments_status')) ? 1 : 0;
        $itemSettings['changelogs_status'] = ($request->has('item.changelogs_status')) ? 1 : 0;
        $itemSettings['support_status'] = ($request->has('item.support_status')) ? 1 : 0;
        $itemSettings['external_file_link_option'] = ($request->has('item.external_file_link_option')) ? 1 : 0;

        $update = Settings::updateSettings('item', $itemSettings);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }
}