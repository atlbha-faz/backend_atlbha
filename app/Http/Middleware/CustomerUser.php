<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->user_type == 'customer' ) {
            return $next($request);
        }

        return response()->json('You do not have permission');
    }
}
