<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Language;
use App\Http\Controllers\Controller;
use App\Models\BlogArticle;
use App\Models\BlogComment;
use App\Models\Follower;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\ItemCommentReply;
use App\Models\ItemReview;
use App\Models\ItemReviewReply;
use App\Models\Settings;
use App\Models\User;
use Exception;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FakerController extends Controller
{
    public function settings()
    {
        return view('admin.faker.settings');
    }

    public function settingsUpdate(Request $request)
    {
        $rules = [
            'faker.api_provider' => ['required', 'string', 'in:gemini,openai'],
        ];

        if ($request->api_provider == "gemini") {
            $rules['faker.gemini_api_key'] = ['nullable', 'string'];
        } else {
            $rules['faker.openai_api_key'] = ['nullable', 'string'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $requestData = $request->except('_token');

        $update = Settings::updateSettings('faker', $requestData['faker']);
        if (!$update) {
            toastr()->error(translate('Updated Error'));
            return back();
        }

        toastr()->success(translate('Updated Successfully'));
        return back();
    }

    public function tools()
    {
        return view('admin.faker.tools.index');
    }

    public function tool($tool)
    {
        abort_if(!File::exists(resource_path("views/admin/faker/tools/{$tool}.blade.php")), 404);

        return view('admin.faker.tools.' . $tool, ['tool' => $tool]);
    }

    public function generate(Request $request, $tool)
    {
        $faker = settings('faker');
        $apiKey = @$faker->api_provider == "gemini" ? @$faker->gemini_api_key : @$faker->openai_api_key;
        if (empty($apiKey)) {
            toastr()->error(translate('API key is missing'));
            return back()->withInput();
        }

        abort_if(!File::exists(resource_path("views/admin/faker/tools/{$tool}.blade.php")), 404);

        try {
            $method = 'handleFake' . Str::studly($tool) . 'Generating';
            if ($this->{$method}($request)) {
                toastr()->success(translate('Generating Completed'));
                return back()->withInput();
            }

            toastr()->error(translate('Generating Failed'));
            return back()->withInput();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    private function handleFakeUsersGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_number' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $faker = Faker::create();

        for ($i = 0; $i < $request->users_number; $i++) {
            $user = User::create([
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make(Str::random(12)),
            ]);

            $user->addCountryBadge();
        }
        $user->addCountryBadge();

        return true;
    }

    private function handleFakeFollowersGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => ['required', 'integer', 'exists:users,id'],
            'followers_number' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $total = $request->followers_number;

        $user = User::where('id', $request->user)->active()->firstOrFail();

        $existingFollowerIds = Follower::where('following_id', $user->id)->pluck('follower_id')->toArray();
        $userIds = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $existingFollowerIds)
            ->pluck('id')
            ->toArray();

        if (count($userIds) < $total) {
            throw new Exception(translate('Not enough unique users to create the requested number of followers or some users already followed.'));
        }

        $selectedFollowerIds = array_rand(array_flip($userIds), $total);

        foreach ($selectedFollowerIds as $followerId) {
            $follower = new Follower();
            $follower->follower_id = $followerId;
            $follower->following_id = $user->id;
            $follower->save();
        }

        return true;
    }

    private function handleFakeItemSalesGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item' => ['required', 'integer', 'exists:items,id'],
            'sales_number' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $item = Item::where('id', $request->item)->approved()->firstOrFail();

        $author = $item->author;

        $totalSales = $request->sales_number;
        $totalSalesAmount = $item->price->regular * $totalSales;

        $item->increment('total_sales', $totalSales);
        $item->increment('total_sales_amount', $totalSalesAmount);

        $author->increment('total_sales', $totalSales);
        $author->increment('total_sales_amount', $totalSalesAmount);

        return true;
    }

    private function handleFakeItemCommentsGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item' => ['required', 'integer', 'exists:items,id'],
            'comments_number' => ['required', 'integer', 'min:1', 'max:1000'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $total = $request->comments_number;

        $item = Item::where('id', $request->item)->approved()->firstOrFail();

        if (User::where('id', '!=', $item->author->id)->active()->count() < $total) {
            throw new Exception(translate('The number of comments is more than your users'));
        }

        $userIds = User::where('id', '!=', $item->author->id)->pluck('id')->toArray();

        $language = Language::get(getLocale());

        for ($i = 0; $i < $total; $i++) {

            $commentPrompt = 'Write a realistic comment in ' . $language . ' like a person asking for something in comments
            about a digital item name "' . $item->name . '" do not write item title in comment. do not include any changeable parameters like [].
            Do not include special characters. return the comment text only do not return any extra texts';

            $commentText = $this->callAPI($commentPrompt);

            if (!isset($commentText)) {
                throw new Exception(translate('Invalid comment response structure'));
            }

            $userId = $userIds[array_rand($userIds)];

            $commentDate = $this->randomDate($request->date_from, $request->date_to);

            $comment = new ItemComment();
            $comment->user_id = $userId;
            $comment->author_id = $item->author->id;
            $comment->item_id = $item->id;
            $comment->created_at = $commentDate;
            $comment->updated_at = $commentDate;
            $comment->save();

            $commentReply = new ItemCommentReply();
            $commentReply->item_comment_id = $comment->id;
            $commentReply->user_id = $comment->user->id;
            $commentReply->body = trim($commentText);
            $commentReply->created_at = $commentDate;
            $commentReply->updated_at = $commentDate;
            $commentReply->save();

            if ($request->has('with_author_reply')) {

                $replyPrompt = 'Write a realistic comment reply in ' . $language . ' for this comment "' . $commentReply->body . '"
                    like an author of the item replying to the comment. do not include any changeable parameters like [].
                    Do not include special characters. return the reply text only do not return any extra texts';

                $replyText = $this->callAPI($replyPrompt);

                if (!isset($replyText)) {
                    throw new Exception(translate('Invalid comment reply response structure'));
                }

                $replyDate = $this->randomDate($commentReply->created_at, $request->date_to);

                $commentReply = new ItemCommentReply();
                $commentReply->item_comment_id = $comment->id;
                $commentReply->user_id = $item->author->id;
                $commentReply->body = trim($replyText);
                $commentReply->created_at = $replyDate;
                $commentReply->updated_at = $replyDate;
                $commentReply->save();
            }

        }

        return true;
    }

    private function handleFakeItemReviewsGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item' => ['required', 'integer', 'exists:items,id'],
            'reviews_number' => ['required', 'integer', 'min:1', 'max:1000'],
            'min_stars' => ['required', 'integer', 'min:1', 'max:5'],
            'max_stars' => ['required', 'integer', 'min:1', 'max:5'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $total = $request->reviews_number;

        $item = Item::where('id', $request->item)->approved()
            ->with('reviews')->firstOrFail();

        $itemReviewsUsersIds = $item->reviews->pluck('user_id')->toArray();
        $usersCount = User::where('id', '!=', $item->author->id)
            ->whereNotIn('id', $itemReviewsUsersIds)
            ->active()->count();

        if ($usersCount < $total) {
            throw new Exception(translate('The number of reviews is more than remaining users'));
        }

        $userIds = User::where('id', '!=', $item->author->id)
            ->whereNotIn('id', $itemReviewsUsersIds)
            ->active()
            ->pluck('id')->toArray();

        $language = Language::get(getLocale());

        for ($i = 0; $i < $total; $i++) {

            $stars = rand($request->min_stars, $request->max_stars);

            $reviewSubjectPrompt = 'Write a realistic review subject in ' . $language . ' for a digital item named "' . $item->name . '" that a person purchased and rated.
            The subject should be between 30 to 80 characters long. write a unique subject do not use any existing subject. Do not include the item name or title in the subject.
            Do not include any changeable parameters like []. The review stars will be ' . $stars . '. The subject should reflect the rating given.
            do not use special characters on subject use the text only. Return only the subject text and nothing else.';

            $reviewSubjectData = $this->callAPI($reviewSubjectPrompt);

            if (!isset($reviewSubjectData)) {
                throw new Exception(translate('Invalid subject response structure'));
            }

            $reviewSubject = $reviewSubjectData;

            $reviewBodyPrompt = 'Write a realistic review in ' . $language . ' like a person purchased and rated a digital item named "' . $item->name . '".
            The review subject is "' . $reviewSubject . '". Do not include the item name or title in the review.
            Do not include the review subject in the review body. Do not include any changeable parameters like [].
            Do not include special characters. Return the review text only, do not return any extra texts.';

            $reviewBody = $this->callAPI($reviewBodyPrompt);

            if (!isset($reviewBody)) {
                throw new Exception(translate('Invalid review response structure'));
            }

            $reviewDate = $this->randomDate($request->date_from, $request->date_to);

            $userId = $userIds[array_rand($userIds)];

            $review = ItemReview::updateOrCreate(
                ['user_id' => $userId, 'item_id' => $item->id],
                [
                    'author_id' => $item->author->id,
                    'stars' => $stars,
                    'subject' => shorterText($reviewSubject, 100),
                    'body' => $reviewBody,
                    'created_at' => $reviewDate,
                    'updated_at' => $reviewDate,
                ]);

            if ($review) {
                if ($review->reply) {
                    $review->reply->delete();
                }

                if ($request->has('with_author_reply')) {
                    $replyPrompt = 'Write a realistic review reply in ' . $language . ' for this review "' . $review->body . '"
                    like an author of the item replying to the user review. Do not include special characters. do not include any changeable parameters like [],
                    Return the review text only, do not return any extra texts.';

                    $replyBody = $this->callAPI($replyPrompt);

                    if (!isset($replyBody)) {
                        throw new Exception(translate('Invalid review response structure'));
                    }

                    $reviewReplyDate = $this->randomDate($review->created_at, $request->date_to);

                    $itemReviewReply = new ItemReviewReply();
                    $itemReviewReply->item_review_id = $review->id;
                    $itemReviewReply->user_id = $item->author->id;
                    $itemReviewReply->body = $replyBody;
                    $itemReviewReply->created_at = $reviewReplyDate;
                    $itemReviewReply->updated_at = $reviewReplyDate;
                    $itemReviewReply->save();
                }
            }
        }

        return true;
    }

    private function handleFakeBlogCommentsGenerating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article' => ['required', 'integer', 'exists:blog_articles,id'],
            'comments_number' => ['required', 'integer', 'min:1', 'max:1000'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                throw new Exception($error);
            }
        }

        $total = $request->comments_number;

        if (User::active()->count() < $total) {
            throw new Exception(translate('The number of comments is more than your users'));
        }

        $blogArticle = BlogArticle::where('id', $request->article)->firstOrFail();

        $userIds = User::pluck('id')->toArray();

        $language = Language::get(getLocale());

        for ($i = 0; $i < $total; $i++) {

            $commentPrompt = 'Write a realistic comment in ' . $language . ' like a person leaves a comment for a blog article with title "' . $blogArticle->title . '".
            Do not include the blog title in the comment. Do not include special characters. Do not include any changeable parameters like [].
            Return the comment text only.';

            $commentText = $this->callAPI($commentPrompt);

            if (!isset($commentText)) {
                throw new Exception(translate('Invalid comment response structure'));
            }

            $userId = $userIds[array_rand($userIds)];

            $commentDate = $this->randomDate($request->date_from, $request->date_to);

            $blogComment = new BlogComment();
            $blogComment->user_id = $userId;
            $blogComment->body = $commentText;
            $blogComment->status = BlogComment::STATUS_PUBLISHED;
            $blogComment->blog_article_id = $blogArticle->id;
            $blogComment->created_at = $commentDate;
            $blogComment->updated_at = $commentDate;
            $blogComment->save();
        }

        return true;
    }

    private function callAPI($prompt)
    {
        $client = new Client();
        $apiProvider = @settings('faker')->api_provider;

        if ($apiProvider == "gemini") {
            $apiKey = @settings('faker')->gemini_api_key;

            $endpoint = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=' . $apiKey;
            $response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $response = json_decode($responseBody, true);

            if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                return $response['candidates'][0]['content']['parts'][0]['text'];
            }
        } else {
            $apiKey = @settings('faker')->openai_api_key;

            $endpoint = 'https://api.openai.com/v1/chat/completions';
            $response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' => [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 500,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();
            $response = json_decode($responseBody, true);

            if (isset($response['choices'][0]['message']['content'])) {
                return $response['choices'][0]['message']['content'];
            }
        }

        return null;
    }

    private function randomDate($startDate, $endDate)
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

        return date('Y-m-d H:i:s', $randomTimestamp);
    }
}
