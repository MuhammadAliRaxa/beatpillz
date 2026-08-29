<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $categories->where('name', 'like', $searchTerm)
                ->OrWhere('slug', 'like', $searchTerm);
        }

        $categories = $categories->get();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function sortable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the table')]);
        }

        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $category = Category::find($id);
            $category->sort_id = ($sortOrder + 1);
            $category->save();
        }

        return response()->json(['success' => true]);
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:categories'],
            'title' => ['nullable', 'string', 'max:70'],
            'description' => ['nullable', 'string', 'max:150'],
            'regular_buyer_fee' => ['required', 'numeric', 'min:0'],
            'extended_buyer_fee' => ['required', 'numeric', 'min:0'],
            'file_type' => ['required', 'integer', 'in:' . implode(',', array_keys(Category::getFileTypeOptions()))],
            'thumbnail_width' => ['required', 'integer', 'min:1'],
            'thumbnail_height' => ['required', 'integer', 'min:1'],
            'main_file_types' => ['required', 'string'],
            'max_preview_file_size' => ['required', 'integer', 'min:1'],

        ];

        if ($request->file_type != Category::FILE_TYPE_FILE_WITH_AUDIO_PREVIEW) {
            $rules['preview_image_width'] = ['required', 'integer', 'min:1'];
            $rules['preview_image_height'] = ['required', 'integer', 'min:1'];
        } else {
            $request->preview_image_width = null;
            $request->preview_image_height = null;

        }

        if ($request->file_type == Category::FILE_TYPE_FILE_WITH_IMAGE_PREVIEW) {
            $rules['maximum_screenshots'] = ['required', 'integer', 'min:1', 'max:100'];
        } else {
            $request->maximum_screenshots = null;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        if ($request->max_preview_file_size > @settings('item')->max_file_size) {
            toastr()->error(translate('The preview file size cannot be greater than the max upload file size'));
            return back();
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->regular_buyer_fee = $request->regular_buyer_fee;
        $category->extended_buyer_fee = $request->extended_buyer_fee;
        $category->file_type = $request->file_type;
        $category->thumbnail_width = $request->thumbnail_width;
        $category->thumbnail_height = $request->thumbnail_height;
        $category->preview_image_width = $request->preview_image_width;
        $category->preview_image_height = $request->preview_image_height;
        $category->maximum_screenshots = $request->maximum_screenshots;
        $category->main_file_types = $request->main_file_types;
        $category->max_preview_file_size = ($request->max_preview_file_size * 1048576);
        $category->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:categories,slug,' . $category->id],
            'title' => ['nullable', 'string', 'max:70'],
            'description' => ['nullable', 'string', 'max:150'],
            'regular_buyer_fee' => ['required', 'numeric', 'min:0'],
            'extended_buyer_fee' => ['required', 'numeric', 'min:0'],
            'thumbnail_width' => ['required', 'integer', 'min:1'],
            'thumbnail_height' => ['required', 'integer', 'min:1'],
            'main_file_types' => ['required', 'string'],
            'max_preview_file_size' => ['required', 'integer', 'min:1'],
        ];

        if ($category->file_type != Category::FILE_TYPE_FILE_WITH_AUDIO_PREVIEW) {
            $rules['preview_image_width'] = ['required', 'integer', 'min:1'];
            $rules['preview_image_height'] = ['required', 'integer', 'min:1'];
        } else {
            $request->preview_image_width = null;
            $request->preview_image_height = null;
        }

        if ($category->file_type == Category::FILE_TYPE_FILE_WITH_IMAGE_PREVIEW) {
            $rules['maximum_screenshots'] = ['required', 'integer', 'min:1', 'max:100'];
        } else {
            $request->maximum_screenshots = null;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if ($request->max_preview_file_size > @settings('item')->max_file_size) {
            toastr()->error(translate('The preview file size cannot be greater than the max upload file size'));
            return back();
        }

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->regular_buyer_fee = $request->regular_buyer_fee;
        $category->extended_buyer_fee = $request->extended_buyer_fee;
        $category->thumbnail_width = $request->thumbnail_width;
        $category->thumbnail_height = $request->thumbnail_height;
        $category->preview_image_width = $request->preview_image_width;
        $category->preview_image_height = $request->preview_image_height;
        $category->maximum_screenshots = $request->maximum_screenshots;
        $category->main_file_types = $request->main_file_types;
        $category->max_preview_file_size = ($request->max_preview_file_size * 1048576);
        $category->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(Category $category)
    {
        if ($category->items->count() > 0) {
            toastr()->error(translate('The selected category has items, it cannot be deleted'));
            return back();
        }

        if ($category->subCategories->count() > 0) {
            toastr()->error(translate('The selected category has subCategories, it cannot be deleted'));
            return back();
        }

        $category->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}