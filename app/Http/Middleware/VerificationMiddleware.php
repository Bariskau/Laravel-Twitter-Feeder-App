<?php

namespace App\Http\Middleware;

use App\Http\Services\VerificationService;
use Closure;
use Illuminate\Http\Request;

class VerificationMiddleware
{
    /**
     * Exclude these routes from authentication check.
     *
     * @var array
     */
    protected $except = [
        'api/v1/verification',
        'api/v1/verification-codes',
        'api/v1/me',
    ];


    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \App\Exceptions\BaseException
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Has the user verified sms and email?
         */
        if (!in_array($request->path(), $this->except)) {
            VerificationService::checkUser($request->user());
        }

        return $next($request);
    }
}
