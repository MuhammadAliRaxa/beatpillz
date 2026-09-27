<?php

namespace App\Http\Controllers\Admin\Help;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $categories = HelpCategory::all();

        $articles = HelpArticle::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $articles->where('title', 'like', $searchTerm)
                ->OrWhere('slug', 'like', $searchTerm)
                ->OrWhere('body', 'like', $searchTerm)
                ->OrWhere('short_description', 'like', $searchTerm)
                ->orWhereHas('category', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm)
                        ->OrWhere('slug', 'like', $searchTerm);
                });
        }

        if (request()->filled('category')) {
            $articles->where('help_category_id', request('category'));
        }

        $articles = $articles->with('category')->orderbyDesc('id')->paginate(50);
        $articles->appends(request()->only(['search', 'category']));

        return view('admin.help.index', [
            'categories' => $categories,
            'articles' => $articles,
        ]);
    }

    public function slug(Request $request)
    {
        $slug = null;
        if ($request->content != null) {
            $slug = SlugService::createSlug(HelpArticle::class, 'slug', $request->content);
        }
        return response()->json(['slug' => $slug]);
    }

    public function create()
    {
        $categories = HelpCategory::all();
        return view('admin.help.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:help_articles'],
            'category' => ['required', 'integer', 'exists:help_categories,id'],
            'body' => ['required'],
            'short_description' => ['required', 'string', 'max:200'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $article = new HelpArticle();
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->body = $request->body;
        $article->short_description = $request->short_description;
        $article->help_category_id = $request->category;
        $article->save();

        toastr()->success(translate('Created Successfully'));
        return redirect()->route('admin.help.articles.edit', $article->id);
    }

    public function edit(HelpArticle $article)
    {
        $categories = HelpCategory::all();
        return view('admin.help.edit', [
            'categories' => $categories,
            'article' => $article,
        ]);
    }

    public function update(Request $request, HelpArticle $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:help_articles,id,' . $article->id],
            'category' => ['required', 'integer', 'exists:help_categories,id'],
            'body' => ['required'],
            'short_description' => ['required', 'string', 'max:200'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back()->withInput();
        }

        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->body = $request->body;
        $article->short_description = $request->short_description;
        $article->help_category_id = $request->category;
        $article->update();

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function destroy(HelpArticle $article)
    {
        $article->delete();
        toastr()->success(translate('Deleted Successfully'));
        return back();
    }
}
