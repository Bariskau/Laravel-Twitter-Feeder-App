<?php

namespace App\Http\Middleware;

use App\Exceptions\BaseException;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Throwable
     */
    public function handle($request, Closure $next, ...$guards)
    {
        throw_if(!$this->auth->guard($guards[0])->check(), new BaseException('Unauthorized', 403));

        return $next($request);
    }

}
