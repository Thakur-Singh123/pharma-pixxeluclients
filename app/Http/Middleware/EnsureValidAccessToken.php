<?php

namespace App\Http\Middleware;

use App\Helpers\AuthHelper;
use Closure;
use Illuminate\Http\Request;

class EnsureValidAccessToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authState = AuthHelper::checkAuthToken($request);

        if ($authState !== true) {
            return $authState;
        }

        return $next($request);
    }
}