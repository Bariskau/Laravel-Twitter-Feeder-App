<?php

use App\Jobs\UserTweetsJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use function Pest\Laravel\assertDatabaseMissing;

it('can create account', function () {

    Bus::fake([UserTweetsJob::class]);
    $user = User::factory()->make();

    $response = $this->post(route('api.register'),
        array_merge($user->toArray(), [User::PASSWORD => 'secret']),
        ['accept' => 'application/json']
    );

    Bus::assertDispatched(UserTweetsJob::class);

    $response->assertStatus(200);
});

it('can not create account with invalid email and phone number', function () {

    Bus::fake([UserTweetsJob::class]);
    $user = User::factory()->make();

    $user->{User::EMAIL} = 'test';
    $user->{User::PHONE_NUMBER} = 'test';

    $response = $this->post(route('api.register'),
        array_merge($user->toArray(), [User::PASSWORD => 'secret']),
        ['accept' => 'application/json']
    );

    $response->assertJsonCount(2, 'errors');

    Bus::assertNotDispatched(UserTweetsJob::class);

    assertDatabaseMissing($user->getTable(), [
        User::EMAIL        => $user->{User::EMAIL},
        User::PHONE_NUMBER => $user->{User::PHONE_NUMBER}
    ]);

    $response->assertStatus(422);
});

it('can fetch me', function () {
    $user = User::factory()->create();

    withVerification($user)->get(route('api.me'))
        ->assertStatus(200)
        ->assertJsonFragment(['name' => $user->name]);
});


