<?php

namespace App\Http\Middleware\Api;

use App\User;
use Closure;
use phpDocumentor\Reflection\Types\Integer;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('user_id')) {

            $isActive = User::find($request->input('user_id'))->is_active;
            if ( $isActive === 1) {
                return $next($request);
            } else {
                return response()->json('User has not been activated yet ', 403);
            }
        }
    }
}
