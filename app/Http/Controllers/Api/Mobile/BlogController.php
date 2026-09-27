<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * List all published blog articles.
     */
    public function index(Request $request)
    {
        $query = BlogArticle::with('category')->latest();

        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('id', $category);
            });
        }

        $articles = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $articles->map(function ($art) {
                return [
                    'id'                => $art->id,
                    'title'             => $art->title,
                    'slug'              => $art->slug,
                    'short_description' => $art->short_description,
                    'image'             => $art->image ? asset($art->image) : null,
                    'views'             => (int) $art->views,
                    'category'          => $art->category ? [
                        'id'   => $art->category->id,
                        'name' => $art->category->name,
                        'slug' => $art->category->slug,
                    ] : null,
                    'created_at'        => $art->created_at ? $art->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ], 200);
    }

    /**
     * Get single blog article details.
     */
    public function show($slug)
    {
        $article = BlogArticle::where('slug', $slug)->with(['category', 'comments.user'])->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.',
            ], 404);
        }

        $article->increment('views');

        return response()->json([
            'success' => true,
            'article' => [
                'id'                => $article->id,
                'title'             => $article->title,
                'slug'              => $article->slug,
                'body'              => $article->body,
                'short_description' => $article->short_description,
                'image'             => $article->image ? asset($article->image) : null,
                'views'             => (int) $article->views,
                'category'          => $article->category ? $article->category->name : null,
                'created_at'        => $article->created_at ? $article->created_at->toISOString() : null,
            ],
        ], 200);
    }
}
