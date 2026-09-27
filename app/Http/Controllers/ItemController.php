<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\Author\SendAuthorItemReviewNotification;
use App\Models\Item;
use App\Models\ItemChangeLog;
use App\Models\ItemComment;
use App\Models\ItemReview;
use App\Models\ItemReviewReply;
use App\Models\SupportPeriod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\UserBadge;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::approved();

        $items = self::getResultByParams($items);

        $items = $items->paginate(30);

        $items->appends(request()->only(['search', 'min_price', 'max_price',
            'free', 'premium', 'on_sale', 'best_selling', 'trending', 'featured', 'stars', 'date']));

        return theme_view('items.index', ['items' => $items]);
    }

    public function view($slug, $id)
    {
        return theme_view('items.view', $this->getItemPageData($slug, $id));
    }

    public function buyNow(Request $request, $slug, $id)
    {
        $item = Item::where('slug', $slug)->where('id', $id)
            ->approved()->purchasingEnabled()->firstOrFail();

        $rules = [
            'license_type' => ['required', 'integer', 'min:1', 'max:2'],
        ];

        $supportPeriod = null;
        if (@settings('item')->support_status && $item->isSupported()) {
            $supportPeriod = SupportPeriod::where('id', $request->support)->firstOrFail();
            $rules['support'] = ['required', 'integer', 'exists:support_periods,id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error' => $error]);
            }
        }

        $price = $item->price->regular;
        if ($request->license_type == 2) {
            $price = $item->price->extended;
        }

        $transactionTotalAmount = $price;

        $support = null;
        if ($supportPeriod) {
            $supportPrice = (($price * $supportPeriod->percentage) / 100);
            $support = [
                'name' => $supportPeriod->name,
                'title' => $supportPeriod->title,
                'days' => $supportPeriod->days,
                'percentage' => $supportPeriod->percentage,
                'price' => round($supportPrice, 2),
                'quantity' => 1,
                'total' => round($supportPrice, 2),
            ];

            $transactionTotalAmount += $supportPrice;
        }

        $transaction = new Transaction();
        $transaction->user_id = authUser()->id;
        $transaction->amount = $transactionTotalAmount;
        $transaction->total = $transactionTotalAmount;
        $transaction->type = Transaction::TYPE_PURCHASE;
        $transaction->save();

        $transactionItem = new TransactionItem();
        $transactionItem->transaction_id = $transaction->id;
        $transactionItem->item_id = $item->id;
        $transactionItem->license_type = $request->license_type;
        $transactionItem->price = $price;
        $transactionItem->support = $support;
        $transactionItem->total = $price;
        $transactionItem->save();

        return redirect()->route('checkout.index', hash_encode($transaction->id));
    }

    public function changelogs($slug, $id)
    {
        $data = $this->getItemPageData($slug, $id);

        $changelogs = ItemChangeLog::where('item_id', $id)
            ->orderbyDesc('id')->paginate(20);

        return theme_view('items.changelogs', ['changelogs' => $changelogs] + $data);
    }

    public function comments($slug, $id)
    {
        return theme_view('items.comments', $this->getItemPageData($slug, $id));
    }

    public function comment($slug, $id, $comment_id)
    {
        $data = $this->getItemPageData($slug, $id);

        $comment = ItemComment::where('id', $comment_id)->where('item_id', $id)
            ->with('user')->firstOrFail();

        return theme_view('items.comment', ['comment' => $comment] + $data);
    }

    public function reviews($slug, $id)
    {
        $data = $this->getItemPageData($slug, $id);

        $reviews = ItemReview::where('item_id', $id)
            ->with('user')->orderbyDesc('id')->paginate(30);

        return theme_view('items.reviews', ['reviews' => $reviews] + $data);
    }

    public function reviewsStore(Request $request, $slug, $id)
    {
        $item = Item::where('slug', $slug)->where('id', $id)
            ->approved()->firstOrFail();

        $user = authUser();

        if ($user->hasPurchasedItem($item->id)) {
            $validator = Validator::make($request->all(), [
                'review_stars' => ['required', 'integer', 'min:1', 'max:5'],
                'subject' => ['required', 'string', 'block_patterns', 'max:100'],
                'review' => ['nullable', 'string', 'block_patterns', 'max:1200'],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    toastr()->error($error);
                }
                return back();
            }

            $author = $item->author;

            $review = ItemReview::updateOrCreate(
                ['user_id' => $user->id, 'item_id' => $item->id],
                [
                    'author_id' => $author->id,
                    'stars' => $request->review_stars,
                    'subject' => $request->subject,
                    'body' => $request->review,
                    'created_at' => Carbon::now(),
                ]);

            if ($review) {
                if ($review->reply) {
                    $review->reply->delete();
                }
                dispatch(new SendAuthorItemReviewNotification($review));
                toastr()->success(translate('Your review has been successfully published'));
            }
        }

        return back();
    }

    public function review($slug, $id, $review_id)
    {
        $data = $this->getItemPageData($slug, $id);

        $review = ItemReview::where('id', $review_id)->where('item_id', $id)
            ->with('user')->firstOrFail();

        return theme_view('items.review', ['review' => $review] + $data);
    }

    public function reviewsReply(Request $request, $slug, $id, $review_id)
    {
        $item = Item::where('slug', $slug)->where('id', $id)
            ->approved()->firstOrFail();

        $review = ItemReview::where('id', $review_id)
            ->where('item_id', $item->id)->firstOrFail();

        $user = authUser();

        if ($review->body && $user->id == $item->author->id) {
            $validator = Validator::make($request->all(), [
                'reply' => ['required', 'string', 'block_patterns', 'max:1200'],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    toastr()->error($error);
                }
                return back();
            }

            $itemReviewReply = new ItemReviewReply();
            $itemReviewReply->item_review_id = $review->id;
            $itemReviewReply->user_id = $user->id;
            $itemReviewReply->body = $request->reply;
            $itemReviewReply->save();

            toastr()->success(translate('Your reply has been successfully published'));
        }

        return back();
    }

    protected function getItemPageData($slug, $id)
    {
        $item = Item::where('slug', $slug)->where('id', $id)
            ->approved()->firstOrFail();

        $userBadges = UserBadge::where('user_id', $item->author->id)
            ->with('badge')->get();

        $authorItems = Item::where('author_id', $item->author_id)
            ->whereNot('id', $item->id)
            ->approved()
            ->inRandomOrder()
            ->limit(6)->get();

        $similarItems = Item::query();

        if ($item->subCategory) {
            $similarItems->where('sub_category_id', $item->subCategory->id);
        } else {
            $similarItems->where('category_id', $item->category->id);
        }

        $similarItems = $similarItems->whereNot('id', $item->id)
            ->whereNot('author_id', $item->author->id)
            ->approved()
            ->inRandomOrder()
            ->limit(6)->get();

        return [
            'item' => $item,
            'userBadges' => $userBadges,
            'authorItems' => $authorItems,
            'similarItems' => $similarItems,
        ];
    }

    public function preview($id)
    {
        $item = Item::where('id', decrypt($id))
            ->whereNotNull('demo_link')->approved()->firstOrFail();

        return theme_view('items.preview', ['item' => $item]);
    }

    public function support($slug, $id)
    {
        $data = $this->getItemPageData($slug, $id);

        return theme_view('items.support', $data);
    }

    public function freeDownload(Request $request, $id)
    {
        $item = Item::where('id', hash_decode($id))
            ->free()->approved()->firstOrFail();

        try {
            $response = $item->download();
            if (isset($response->type) && $response->type == "error") {
                throw new Exception($response->message);
            }
            $item->increment('free_downloads');
            return $response;
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }

    }

    public function freeExternalDownload($id)
    {
        $item = Item::where('id', hash_decode($id))
            ->free()->approved()->firstOrFail();

        $item->increment('free_downloads');
        return redirect($item->main_file);
    }

    public function freeLicense($id)
    {
        $item = Item::where('id', decrypt($id))
            ->free()->approved()->firstOrFail();

        return theme_view('items.free-license', ['item' => $item]);
    }

    public function premiumDownload(Request $request, $id)
    {
        $item = Item::where('id', hash_decode($id))
            ->premium()->approved()->firstOrFail();

        $user = authUser();

        if ($user->isSubscribed() && $item->author->id != $user->id) {
            if ($user->subscription->isExpired()) {
                toastr()->error(translate('Your subscription is expired'));
                return redirect()->route('workspace.settings.subscription');
            }

            if ($user->subscription->isDailyLimitReached()) {
                toastr()->error(translate('You have exceeded your daily download limit. Upgrade your plan to get more downloads.'));
                return redirect()->route('premium.index');
            }

            try {
                $response = $item->download();
                if (isset($response->type) && $response->type == "error") {
                    throw new Exception($response->message);
                }
                $user->subscription->increment('total_downloads');
                return $response;
            } catch (Exception $e) {
                toastr()->error($e->getMessage());
                return back();
            }
        }

        return back();
    }

    public function premiumExternalDownload($id)
    {
        $item = Item::where('id', hash_decode($id))
            ->premium()->approved()->firstOrFail();

        $user = authUser();

        if ($user->isSubscribed() && $item->author->id != $user->id) {
            if ($user->subscription->isExpired()) {
                toastr()->error(translate('Your subscription is expired'));
                return redirect()->route('workspace.settings.subscription');
            }

            if ($user->subscription->isDailyLimitReached()) {
                toastr()->error(translate('You have exceeded your daily download limit. Upgrade your plan to get more downloads.'));
                return redirect()->route('premium.index');
            }

            $user->subscription->increment('total_downloads');
            return redirect($item->main_file);
        }

        return back();
    }

    public function premiumLicense($id)
    {
        $item = Item::where('id', decrypt($id))
            ->premium()->approved()->firstOrFail();

        $user = authUser();

        abort_if(!$user->isSubscribed() || $item->author->id == $user->id, 404);

        return theme_view('items.premium-license', ['item' => $item]);
    }

    public static function getResultByParams($items)
    {
        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $searchTermStart = request('search') . '%';

            $items->where(function ($query) use ($searchTerm, $searchTermStart) {
                $query->where('name', 'like', $searchTermStart)
                    ->orWhere(function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm)
                            ->orWhere('slug', 'like', $searchTerm)
                            ->orWhere('description', 'like', $searchTerm)
                            ->orWhere('options', 'like', $searchTerm)
                            ->orWhere('demo_link', 'like', $searchTerm)
                            ->orWhere('tags', 'like', $searchTerm)
                            ->orWhereHas('category', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm);
                            })
                            ->orWhereHas('subCategory', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm);
                            });
                    });
            });
        }

        if (licenseType(2) && @settings('premium')->status && request()->filled('premium')) {
            $items->premium();
        }

        if (request()->filled('free')) {
            $items->free();
        }

        if (request()->filled('on_sale')) {
            $items->onDiscount();
        }

        if (request()->filled('best_selling')) {
            $items->bestSelling();
        }

        if (request()->filled('trending')) {
            $items->trending();
        }

        if (request()->filled('featured')) {
            $items->featured();
        }

        if (request()->filled('stars')) {
            $items->where('avg_reviews', '>=', request('stars'));
        }

        if (request()->filled('date')) {
            $dateFilter = request('date');
            switch ($dateFilter) {
                case 'this_month':
                    $items->whereBetween('created_at', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ]);
                    break;
                case 'last_month':
                    $items->whereBetween('created_at', [
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth(),
                    ]);
                    break;
                case 'this_year':
                    $items->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'last_year':
                    $items->whereYear('created_at', Carbon::now()->subYear()->year);
                    break;
                default:
                    break;
            }
        }

        if (request()->filled('min_price') || request()->filled('max_price')) {
            $minPrice = request('min_price') ?? null;
            $maxPrice = request('max_price') ?? null;
            $items->join('categories', 'categories.id', '=', 'items.category_id')
                ->where(function ($query) use ($minPrice, $maxPrice) {
                    if (!is_null($minPrice)) {
                        $query->whereRaw('(items.regular_price + categories.regular_buyer_fee) >= ?', [$minPrice]);
                    }
                    if (!is_null($maxPrice)) {
                        $query->whereRaw('(items.regular_price + categories.regular_buyer_fee) <= ?', [$maxPrice]);
                    }
                });
        }

        if (request()->filled('best_selling')) {
            $items->orderbyDesc('items.total_sales');
        } else {
            if (isset($searchTermStart)) {
                $items->orderByRaw("CASE WHEN name LIKE ? THEN 1 ELSE 2 END", [$searchTermStart])->orderByDesc('items.id');
            } else {
                $items->orderByDesc('items.id');
            }
        }

        return $items;
    }
}
