<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheckPermission
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
        // $routeName = strtolower($request->route()->getName());

        if (!is_null($user)) {

            if ( $user->user_type == 'admin') {
                $hasPermission = true;
            }

            if ($user->can($request->route()->getName())) {
                $hasPermission = true;
            }

            if ($hasPermission) {
                return $next($request);
            }
            // dd(  $request->route()->getName());
            // return response('You dont have access to the requested page', 403);
            $response = [
                'success' =>true ,
                'message'=>['en' => 'You dont have access to the requested page', 'ar' => 'ليس لديك صلاحية الوصول']
        
            ];
            return response()->json( $response, 403);

            // abort(Response::HTTP_FORBIDDEN, "You don't have access to the requested page. (" . $request->route()->getName() . ")");
        }

        return redirect()->to('/login')->with('message', 'Session has expired, please log in.');
    
    }
}
