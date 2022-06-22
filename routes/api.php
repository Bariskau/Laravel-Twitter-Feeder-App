<?php

use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\SingleUserCheck;
use App\Http\Middleware\VerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->name('api.')->group(function () {
    // Auth
    Route::post('/register', [UserController::class, 'store'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::middleware(['auth:api', VerificationMiddleware::class, SingleUserCheck::class])->group(function ($route) {

        // me
        $route->get('/me', [UserController::class, 'me'])->name('me');

        // user
        $route->post('/logout', [UserController::class, 'logout']);
        $route->post('/refresh', [UserController::class, 'refresh']);

        // tweets
        $route->get('/tweets/user/{userId}', [TweetController::class, 'userTweets'])->name('tweets.user');
        $route->get('/tweets/auth', [TweetController::class, 'authTweets'])->name('tweets.auth');
        $route->post('/tweets/get-last-tweets', [TweetController::class, 'getLastTweets'])->name('tweets.last');
        $route->put('/tweets/{tweet}', [TweetController::class, 'update'])->middleware('can:update,tweet')->name('tweets.update');
        $route->apiResource('tweets', TweetController::class)->except(['update']);

        // verification
        $route->post('/verification', [VerificationController::class, 'verify']);
        $route->post('/verification-generate', [VerificationController::class, 'generate']);
        $route->get('/verification-codes', [VerificationController::class, 'getVerificationCodes']);
    });
});
