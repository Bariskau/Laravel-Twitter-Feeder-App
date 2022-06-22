<?php

namespace App\Http\Services;

use App\Enums\TweetStatus;
use App\Http\Filters\TweetFilter;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TweetService
{
    /**
     * @param int $perPage
     * @return mixed
     */
    public function getTweets(int $perPage)
    {
        return Tweet::query()
            ->{TweetStatus::active}()
            ->orderBy(Tweet::TWEET_DATE, 'desc')
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * @param TweetFilter $filter
     * @param int $perPage
     * @return mixed
     */
    public function getAuthTweets(TweetFilter $filter, int $perPage)
    {
        $user = auth()->user();

        return $user->tweets()
            ->filter($filter)
            ->orderBy(Tweet::TWEET_DATE, 'desc')
            ->paginate($perPage);
    }

    /**
     * @param string $userId
     * @param int $perPage
     * @return mixed
     */
    public function getUserTweets(string $userId, int $perPage)
    {
        $user = User::findByUuid($userId);

        return $user->tweets()
            ->{TweetStatus::active}()
            ->orderBy(Tweet::TWEET_DATE, 'desc')
            ->paginate($perPage);
    }

    /**
     * @param $data
     * @param int $userId
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded
     */
    public function createUserTweets($data, int $userId)
    {
        foreach ($data as $tweet) {
            $newTweet = Tweet::query()->firstOrCreate([
                Tweet::TWEET_ID => $tweet->id,
            ], [
                Tweet::USER_ID    => $userId,
                Tweet::TWEET_ID   => $tweet->id,
                Tweet::TWEET      => $tweet->text,
                Tweet::TWEET_DATE => $tweet->created_at,
            ]);

            if ($newTweet->wasRecentlyCreated && ($media = (optional($tweet->entities)->media))) {
                try {
                    $this->saveTweetMedia($newTweet, $media);
                } catch (\Exception $e) {
                    Log::error(json_encode($e));
                }
            }
        }
    }

    /**
     * @param Tweet $tweet
     * @param array $media
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig|\Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded
     */
    protected function saveTweetMedia(Tweet $tweet, array $media)
    {
        foreach ($media as $tweetMedia) {
            $tweet->addMediaFromUrl($tweetMedia->media_url_https)
                ->toMediaCollection(Tweet::MEDIA_COLLECTION);
        }
    }

    /**
     * @param Tweet $tweet
     * @param string|null $tweetData
     */
    public function updateTweet(Tweet $tweet, string $tweetData = null)
    {
        if ($tweetData) {
            $tweet->{Tweet::TWEET} = $tweetData;
        }

        $data = [
            Tweet::STATUS => TweetStatus::active
        ];

        if ($tweet->isDirty(Tweet::TWEET)) {
            $data = array_merge($data, [Tweet::TWEET => $tweetData]);
        }

        $tweet->update($data);
    }

}
