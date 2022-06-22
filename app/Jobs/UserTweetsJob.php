<?php

namespace App\Jobs;

use App\Http\Services\TweetService;
use App\Models\User;
use App\Services\TwitterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserTweetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

    const count = 20;
    const responseFormat = 'json';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param TwitterService $twitterService
     * @param TweetService $tweetService
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(TwitterService $twitterService, TweetService $tweetService)
    {
        $res = $twitterService
            ->getUserTimeline($this->user->{User::TWITTER_ADDRESS}, self::count, self::responseFormat);

        $tweetService->createUserTweets($res, $this->user->id);
    }
}
