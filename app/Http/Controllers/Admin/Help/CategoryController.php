<?php

namespace App\Http\Controllers\Admin\Help;

use App\Http\Controllers\Controller;
use App\Models\HelpCategory;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = HelpCategory::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $categories->where('name', 'like', $searchTerm);
        }

        $categories = $categories->get();

        return view('admin.help.categories.index', ['categories' => $categories]);
    }

    public function sortable(Request $request)
    {
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the table')]);
        }

        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $category = HelpCategory::find($id);
            $category->sort_id = ($sortOrder + 1);
            $category->save();
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.help.categories.create');
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(HelpCategory::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'unique:help_categories', 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $category = new HelpCategory();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->sort_id = (HelpCategory::count() + 1);
        $category->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.help.categories.edit', $category->id);
    }

    public function edit(HelpCategory $category)
    {
        return view('admin.help.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, HelpCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'unique:help_categories,slug,' . $category->id, 'max:255'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(HelpCategory $category)
    {
        if ($category->articles->count() > 0) {
            toastr()->error(translate('The selected category has articles, it cannot be deleted'));
            return back();
        }

        $category->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}
