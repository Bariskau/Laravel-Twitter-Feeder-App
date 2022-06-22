<?php

use App\Enums\TweetStatus;
use App\Jobs\UserTweetsJob;
use App\Models\Tweet;
use App\Models\User;
use App\Services\TwitterService;
use Illuminate\Support\Facades\Bus;
use Mockery\MockInterface;
use function PHPUnit\Framework\assertEquals;

it('can update a tweet', function () {
    $tweet = Tweet::factory()->create();

    withVerification($tweet->user)
        ->put(route('api.tweets.update', [
            'tweet' => $tweet->uuid->toString()
        ], ['accept' => 'application/json']), [
            Tweet::TWEET => 'test'
        ])->assertStatus(204);

    $tweet->refresh();

    $this->assertEquals($tweet->{Tweet::TWEET}, 'test');
    $this->assertEquals($tweet->{Tweet::STATUS}, TweetStatus::active);
});

it('can get last 20 tweet from twitter', function () {
    Bus::fake([UserTweetsJob::class]);

    $tweet = Tweet::factory()->create();

    $this->instance(
        TwitterService::class,
        Mockery::mock(TwitterService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserTimeline')->andReturn();
        })
    );

    withVerification($tweet->user)
        ->post(route('api.tweets.last'))
        ->assertStatus(204);

    Bus::assertDispatched(UserTweetsJob::class);
});


it('can lists all active tweets', function () {
    Tweet::factory()->count(25)->create([
        Tweet::STATUS => TweetStatus::active
    ]);

    withVerification()
        ->get(route('api.tweets.index', ['perPage' => 15]))
        ->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonFragment(['total' => Tweet::query()->count()]);
});

it('can lists auth tweets', function () {
    $user = User::factory()->create();

    Tweet::factory()->count(25)->create([
        Tweet::USER_ID => $user->id,
        Tweet::STATUS  => TweetStatus::active,
    ]);

    $res = withVerification($user)
        ->get(route('api.tweets.auth', ['perPage' => 15]));

    assertEquals($res['data'][0][Tweet::STATUS], TweetStatus::active);

    $res->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonFragment(['total' => Tweet::query()->count()]);

});

it('can lists other user tweets', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    Tweet::factory()->create([
        Tweet::STATUS  => TweetStatus::active,
        Tweet::USER_ID => $otherUser->id
    ]);

    withVerification($user)
        ->get(route('api.tweets.user', [
            'userId' => $otherUser->uuid->toString()
        ]))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['total' => Tweet::query()->count()]);
});


