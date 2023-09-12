<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckStorePermission
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
        $user = auth()->user();
        $hasPermission = false;
        $routeName = strtolower($request->route()->getName());

        if (!is_null($user)) {

            if ($user->hasRole('المالك')) {
                $hasPermission = true;
            }

            if ($user->can($request->route()->getName())) {
                $hasPermission = true;
            }

            if ($hasPermission) {
                return $next($request);
            }
            return response('You dont have access to the requested page', 403);

            // abort(Response::HTTP_FORBIDDEN, "You don't have access to the requested page. (" . $request->route()->getName() . ")");
        }

        return redirect()->to('/login')->with('message', 'Session has expired, please log in.');
    }

}
