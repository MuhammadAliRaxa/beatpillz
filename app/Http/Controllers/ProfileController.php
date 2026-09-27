<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Item;
use App\Models\ItemReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index($username)
    {
        $user = $this->getUserByUsername($username);

        $followers = Follower::where('following_id', $user->id)
            ->with('follower')
            ->orderbyDesc('id')
            ->paginate(12);

        return theme_view('profile.index', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    public function portfolio($username)
    {
        $user = $this->getUserByUsername($username, true);

        $items = Item::where('author_id', $user->id)
            ->approved();

        $searchTerm = request()->input('search');
        if ($searchTerm) {
            $items->where(function (Builder $query) use ($searchTerm) {
                $query->where('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('options', 'like', '%' . $searchTerm . '%')
                    ->orWhere('demo_link', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tags', 'like', '%' . $searchTerm . '%');
            });
        }

        $items = $items->orderByDesc('id')->paginate(21);
        $items->appends(request()->only(['search']));

        return theme_view('profile.portfolio', [
            'user' => $user,
            'items' => $items,
        ]);
    }

    public function followers($username)
    {
        $user = $this->getUserByUsername($username);

        $followers = Follower::where('following_id', $user->id)
            ->with('follower')
            ->orderbyDesc('id')
            ->paginate(21);

        return theme_view('profile.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    public function following($username)
    {
        $user = $this->getUserByUsername($username);

        $followings = Follower::where('follower_id', $user->id)
            ->with('following')
            ->orderbyDesc('id')
            ->paginate(21);

        return theme_view('profile.following', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }

    public function reviews($username)
    {
        $user = $this->getUserByUsername($username, true);

        $reviews = ItemReview::where('author_id', $user->id)
            ->with('user')
            ->orderByDesc('id')
            ->paginate(20);

        return theme_view('profile.reviews', [
            'user' => $user,
            'reviews' => $reviews,
        ]);
    }

    public function sendMail(Request $request, $username)
    {
        $user = User::where('username', $username)
            ->active()
            ->firstOrFail();

        abort_if(!authUser(), 401);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if (authUser()->id == $user->id) {
            toastr()->error(translate('You cannot send a message to yourself'));
            return back()->withInput();
        }

        if (!settings('smtp')->status) {
            toastr()->error(translate('Failed to send the message'));
            return back()->withInput();
        }

        try {
            $subject = translate('Message sent via your :website_name profile from :username', [
                'website_name' => @settings('general')->site_name,
                'username' => authUser()->username,
            ]);

            $email = $user->email;
            $replyTo = authUser()->email;
            $msg = nl2br($request->message);

            Mail::send([], [], function ($message) use ($msg, $email, $subject, $replyTo) {
                $message->to($email)
                    ->replyTo($replyTo)
                    ->subject($subject)
                    ->html($msg);
            });

            toastr()->success(translate('Your message has been sent successfully'));
            return back();

        } catch (Exception $e) {
            toastr()->error(translate('Failed to send the message'));
            return back();
        }
    }

    protected function getUserByUsername($username, $authorCheck = false)
    {
        $query = User::where('username', $username)
            ->whereDataCompleted()
            ->with(['badges' => function ($query) {
                $query->with('badge');
            }]);

        if ($authorCheck) {
            $query->author();
        }

        return $query->firstOrFail();
    }
}
