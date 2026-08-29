<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        $commonCategories = HelpCategory::orderbyDesc('views')->limit(4)->get();

        if (!request()->filled('search')) {
            $categories = HelpCategory::with('articles')->get()->map(function ($category) {
                $category->setRelation('articles', $category->articles->take(8));
                return $category;
            });
        } else {
            $searchTerm = '%' . request('search') . '%';

            $categories = HelpCategory::where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('slug', 'like', $searchTerm);
            })
                ->orWhereHas('articles', function ($query) use ($searchTerm) {
                    $query->where('title', 'like', $searchTerm)
                        ->orWhere('slug', 'like', $searchTerm)
                        ->orWhere('body', 'like', $searchTerm)
                        ->orWhere('short_description', 'like', $searchTerm);
                })
                ->with(['articles' => function ($query) use ($searchTerm) {
                    $query->where('title', 'like', $searchTerm)
                        ->orWhere('slug', 'like', $searchTerm)
                        ->orWhere('body', 'like', $searchTerm)
                        ->orWhere('short_description', 'like', $searchTerm);
                }])
                ->get()
                ->map(function ($category) {
                    $category->setRelation('articles', $category->articles->take(8));
                    return $category;
                });
        }

        return theme_view('help.index', [
            'commonCategories' => $commonCategories,
            'categories' => $categories,
        ]);
    }

    public function category($slug)
    {
        $category = HelpCategory::where('slug', $slug)->firstOrFail();

        incrementViews($category, 'help_category');

        $articles = HelpArticle::where('help_category_id', $category->id)->paginate(20);

        return theme_view('help.category', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    public function article($slug)
    {
        $article = HelpArticle::where('slug', $slug)
            ->with('category')->firstOrFail();

        incrementViews($article, 'help_articles');

        $relatedArticles = HelpArticle::where('help_category_id', $article->category->id)
            ->inRandomOrder()->limit(10)->get();

        return theme_view('help.article', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    public function react(Request $request, $slug)
    {
        $article = HelpArticle::where('slug', $slug)->firstOrFail();

        if ($request->action == 1) {
            $article->increment('likes');
        } elseif ($request->action == 2) {
            $article->increment('dislikes');
        }
        return response()->json([
            'success' => translate('Thanks for your feedback'),
        ]);
    }
}