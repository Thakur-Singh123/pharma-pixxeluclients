<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        //Get auth detail
        $user_detail = Auth::user();
        //Check if user type vendor exits or not
        if ($user_detail->user_type == 'Vendor') {
            return $next($request);
        } else {
           return redirect('login');
        }
    }
}
