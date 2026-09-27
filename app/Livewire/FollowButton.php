<?php

namespace App\Livewire;

use App\Models\Follower;
use Livewire\Component;

class FollowButton extends Component
{
    public $user;

    public $iconButton;

    public $follower;

    public function middleware()
    {
        return ['auth', 'oauth.complete', 'verified', '2fa.verify'];
    }

    public function mount($user)
    {
        $this->user = $user;
        $this->follower = authUser();
    }

    public function followAction()
    {
        if (!$this->follower->isFollowingUser($this->user->id)) {
            $follower = new Follower();
            $follower->follower_id = $this->follower->id;
            $follower->following_id = $this->user->id;
            $follower->save();
        } else {
            $follower = $this->follower->followings()->where('following_id', $this->user->id);
            if ($follower) {
                $follower->delete();

                $this->follower->decrement('total_following');
                $this->user->decrement('total_followers');
            }
        }
    }

    public function render()
    {
        return view('themes.basic.livewire.follow-button');
    }
}