<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CanSaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->user_type === 'MR' && $user->can_sale) {
            return $next($request);
        }
 
        $errorMessage = 'You must be an MR to access this page AND have sales permissions.';
        if ($user_detail && $user_detail->user_type) {
            $errorMessage = 'You are currently logged in as '.$user_detail->user_type.' With sales permission. Please login with other account. ';
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 403,
                'message' => $errorMessage,
            ], 403);
        }

        return redirect()->route('login')->with('error', $errorMessage);
    }

}
