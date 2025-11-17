<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        //Get auth detail
        $user_detail = Auth::user();
        //Check if user type manager exits or not
        if ($user_detail->user_type == 'Manager') {
            return $next($request);
        }  
        
        $errorMessage = 'You must be an Manager to access this page.';
        if ($user_detail && $user_detail->user_type) {
            $errorMessage = 'You are currently logged in as '.$user_detail->user_type.'. Please login with other account.';
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
