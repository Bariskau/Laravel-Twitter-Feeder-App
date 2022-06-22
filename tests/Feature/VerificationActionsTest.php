<?php

use App\Models\User;

it('can not use without verifications', function () {
    $user = User::factory()->create();

    actingAs($user)->get(route('api.tweets.index'))
        ->assertStatus(403)
        ->assertJsonFragment(['status' => 'error']);
});

it('can use with verifications', function () {
    withVerification()->get(route('api.me'))
        ->assertStatus(200);
});



