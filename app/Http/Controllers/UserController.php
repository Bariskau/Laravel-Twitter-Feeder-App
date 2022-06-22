<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use App\Models\User;

class UserController extends Controller
{
    /**
     * POST Login
     *
     * This endpoint allows you to log in and receive a token.
     * <aside class="notice">By default ttl is set to 3600</aside>
     * @responseFile 200 storage/responses/post.login.success.json
     * @responseFile 401 storage/responses/post.login.error.json
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function login(LoginRequest $request, UserService $userService)
    {
        $req = $request->only(User::EMAIL, User::PASSWORD);

        return $userService->login($req);
    }

    /**
     * POST Register
     *
     * This endpoint allows you to register.
     * @responseFile 200 storage/responses/post.register.success.json
     * @responseFile 401 storage/responses/post.register.error.json
     *
     * @header Content-Type application/json
     * @header Accept application/json
     */
    public function store(RegisterRequest $request, UserService $userService)
    {
        $data = $request->only([
            User::NAME,
            User::EMAIL,
            User::PASSWORD,
            User::PHONE_NUMBER,
            User::TWITTER_ADDRESS,
        ]);

        $userService->register($data);

        return response()->json([
            'message' => __('auth.register.success')
        ]);
    }

    /**
     * GET Me
     *
     * This endpoint provides the authenticated user information
     * @responseFile 200 storage/responses/post.me.success.json
     * @header Content-Type application/json
     * @header Accept application/json
     *
     * @authenticated
     */
    public function me()
    {
        $user = auth()->user();

        return UserResource::make($user);
    }

    /**
     * POST Logout
     *
     * This endpoint allows the authenticated user to log out.
     *
     * @response 204
     * @header Content-Type application/json
     * @header Accept application/json
     *
     * @authenticated
     */
    public function logout(UserService $userService)
    {
        return $userService->logout();
    }

    /**
     * POST Refresh Token
     *
     * Renews the token before the ttl expires
     *
     * @responseFile 200 storage/responses/post.login.success.json
     *
     * @header Content-Type application/json
     * @header Accept application/json
     *
     * @authenticated
     */
    public function refresh(UserService $userService)
    {
        return $userService->refresh();
    }
}
