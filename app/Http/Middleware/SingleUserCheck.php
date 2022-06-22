<?php

namespace App\Http\Middleware;

use App\Exceptions\BaseException;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class SingleUserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $user = auth()->user();

        throw_if($user && $this->checkSession($user), new BaseException('SingleStateException', 302));

        return $response;

    }

    /**
     * @param $user
     * @return bool
     */
    protected function checkSession($user): bool
    {
        $token = JWTAuth::fromUser($user);

        $sessionId = JWTAuth::setToken($token)
            ->getPayload()
            ->get('session_id');

        return $sessionId !== $user->session_id;
    }
}
