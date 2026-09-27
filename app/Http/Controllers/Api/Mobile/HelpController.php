<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Get help categories and articles list.
     */
    public function categories()
    {
        $categories = HelpCategory::with(['articles' => function ($q) {
            $q->take(6);
        }])->get();

        return response()->json([
            'success'    => true,
            'categories' => $categories->map(function ($cat) {
                return [
                    'id'          => $cat->id,
                    'name'        => $cat->name,
                    'slug'        => $cat->slug,
                    'description' => $cat->description,
                    'icon'        => $cat->icon,
                    'articles'    => $cat->articles->map(function ($art) {
                        return [
                            'id'                => $art->id,
                            'title'             => $art->title,
                            'slug'              => $art->slug,
                            'short_description' => $art->short_description,
                        ];
                    }),
                ];
            }),
        ], 200);
    }

    /**
     * Get single help article.
     */
    public function article($slug)
    {
        $article = HelpArticle::where('slug', $slug)->with('category')->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'article' => [
                'id'                => $article->id,
                'title'             => $article->title,
                'slug'              => $article->slug,
                'body'              => $article->body,
                'short_description' => $article->short_description,
                'category'          => $article->category ? $article->category->name : null,
                'created_at'        => $article->created_at ? $article->created_at->toISOString() : null,
            ],
        ], 200);
    }

    /**
     * Get frequently asked questions (FAQs).
     */
    public function faqs()
    {
        $faqs = Faq::all();

        return response()->json([
            'success' => true,
            'faqs'    => $faqs->map(function ($faq) {
                return [
                    'id'       => $faq->id,
                    'title'    => $faq->title,
                    'body'     => $faq->body,
                ];
            }),
        ], 200);
    }
}
