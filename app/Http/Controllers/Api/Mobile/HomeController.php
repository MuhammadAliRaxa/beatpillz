<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\CategoryResource;
use App\Http\Resources\Mobile\ItemResource;
use App\Http\Resources\Mobile\UserResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Aggregated home discovery payload for mobile dashboard.
     */
    public function index()
    {
        $featuredBeats = Item::where('is_featured', 1)
            ->where('status', Item::STATUS_APPROVED)
            ->with(['author', 'category', 'subCategory', 'discount'])
            ->latest()
            ->take(8)
            ->get();

        $trendingBeats = Item::where('is_trending', 1)
            ->where('status', Item::STATUS_APPROVED)
            ->with(['author', 'category', 'subCategory', 'discount'])
            ->latest()
            ->take(10)
            ->get();

        $bestSelling = Item::where('is_best_selling', 1)
            ->where('status', Item::STATUS_APPROVED)
            ->with(['author', 'category', 'subCategory', 'discount'])
            ->latest()
            ->take(10)
            ->get();

        $latestBeats = Item::where('status', Item::STATUS_APPROVED)
            ->with(['author', 'category', 'subCategory', 'discount'])
            ->latest()
            ->take(15)
            ->get();

        $categories = Category::with('subCategories')
            ->orderBy('sort_id', 'asc')
            ->take(12)
            ->get();

        $featuredProducers = User::where('is_featured_author', 1)
            ->where('status', User::STATUS_ACTIVE)
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'featured_beats'     => ItemResource::collection($featuredBeats),
                'trending_beats'     => ItemResource::collection($trendingBeats),
                'best_selling_beats' => ItemResource::collection($bestSelling),
                'latest_beats'       => ItemResource::collection($latestBeats),
                'categories'         => CategoryResource::collection($categories),
                'featured_producers' => UserResource::collection($featuredProducers),
            ],
        ], 200);
    }

    /**
     * List all music genres / categories with subcategories.
     */
    public function categories()
    {
        $categories = Category::with('subCategories')
            ->orderBy('sort_id', 'asc')
            ->get();

        return response()->json([
            'success'    => true,
            'categories' => CategoryResource::collection($categories),
        ], 200);
    }
}
