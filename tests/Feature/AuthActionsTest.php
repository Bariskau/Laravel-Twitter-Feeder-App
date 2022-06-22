<?php

use App\Models\User;

it('can use with authentication', function () {
    withVerification()->get(route('api.me'))
        ->assertStatus(200);
});

it('can not use without unauthenticated', function () {
    $this->get(route('api.me'))
        ->assertStatus(403)
        ->assertJsonFragment(['code' => 403]);
});

it('can not use multiple session', function () {

    $user = User::factory()->create([
        User::PASSWORD => 'secret'
    ]);

    $this->post(route('api.login'),
        [
            User::EMAIL => $user->email,
            User::PASSWORD => 'secret'
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['token_type' => 'bearer']);

    $this->get(route('api.me'))
        ->assertStatus(200);

    /**
     * same user authenticated again
     */
    actingAs($user)->get(route('api.me'))
        ->assertStatus(302);
});

