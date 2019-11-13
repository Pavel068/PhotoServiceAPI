<?php

namespace App\Http\Middleware;

use Closure;

class AuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty(trim($request->input('api_token')))) {
            $user = User::where('id', Auth::guard('api')->id())->exists();
            if ($user) {
                return $next($request);
            }
        }
        return response()->json('Invalid Token', 401);
    }
}
