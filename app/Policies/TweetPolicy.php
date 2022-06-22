<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TweetPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Tweet $tweet)
    {
        return $user->is($tweet->user) ?: Response::deny();
    }
}
