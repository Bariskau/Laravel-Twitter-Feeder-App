<?php

namespace App\Http\Controllers;


use App\Http\Filters\TweetFilter;
use App\Http\Requests\Tweet\UpdateRequest;
use App\Http\Resources\TweetResource;
use App\Http\Services\TweetService;
use App\Jobs\UserTweetsJob;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * GET All Tweets
     *
     * List the tweets of the all user.
     * @authenticated
     *
     * @responseFile 200 storage/responses/get.tweets.success.json
     *
     * @queryParam perPage int Number of items per page .
     * @queryParam page int Current Page.
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function index(Request $request, TweetService $tweetService)
    {
        $tweets = $tweetService
            ->getTweets($request->get('perPage', 50));

        return TweetResource::collection($tweets);
    }

    /**
     * GET Authenticated User Tweets
     *
     * List the tweets of the authenticated user.
     * @authenticated
     *
     * @responseFile 200 storage/responses/get.tweets.success.json
     *
     * @queryParam perPage int Number of items per page .
     * @queryParam page int Current Page.
     * @queryParam search string Search keyword.
     * @queryParam type string active|passive Example: active
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function authTweets(Request $request, TweetFilter $filter, TweetService $tweetService)
    {
        $tweets = $tweetService
            ->getAuthTweets($filter, $request->get('perPage', 10));

        return TweetResource::collection($tweets);
    }

    /**
     * GET Show User Tweets
     *
     * List the tweets of the user whose id is given.
     * @authenticated
     *
     * @responseFile 200 storage/responses/get.tweets.success.json
     *
     * @urlParam userId required The ID of the user.
     * @queryParam perPage int Number of items per page .
     * @queryParam page int Current Page.
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function userTweets(Request $request, string $userId, TweetService $tweetService)
    {
        $tweets = $tweetService
            ->getUserTweets($userId, $request->get('perPage', 10));

        return TweetResource::collection($tweets);
    }

    /**
     * PUT Update And Publish Tweet
     *
     * The tweet is updated and published.
     * @authenticated
     *
     * @response 204
     *
     * @urlParam tweetId required The ID of the tweet.
     * @bodyParam  tweet string max:280
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function update(UpdateRequest $request, Tweet $tweet, TweetService $tweetService)
    {
        $tweetData = $request->get(Tweet::TWEET);
        $tweetService->updateTweet($tweet, $tweetData);

        return response()->noContent();
    }

    /**
     * POST Get Last 20 Tweets
     *
     * Get the last 20 tweets of the user from twitter
     * @authenticated
     *
     * @response 204
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function getLastTweets(Request $request)
    {
        $user = $request->user();
        UserTweetsJob::dispatchSync($user);

        return response()->noContent();
    }


}
