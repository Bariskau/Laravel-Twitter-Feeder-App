<?php

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(Tests\TestCase::class)->in('Feature');
uses(RefreshDatabase::class)->in('Feature');

/**
 * Set the currently logged in user for the application.
 *
 * @param Authenticatable $user
 * @return TestCase
 */
function actingAs(Authenticatable $user): TestCase
{
    return test()->actingAs($user, 'api');
}

/**
 * Set the currently logged in user for the application.
 *
 * @param User|null $user
 * @return TestCase
 */
function withVerification(User $user = null): TestCase
{
    if (!$user) {
        $user = User::factory()->create();
    }

    $user->update([
        User::EMAIL_VERIFIED_AT => now(),
        User::SMS_VERIFIED_AT   => now()
    ]);

    return test()->actingAs($user);
}


