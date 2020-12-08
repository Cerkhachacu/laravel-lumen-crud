<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->role());
        if(intval($request->input('role')) !== 1) return response()->json([
            'success'=> false,
            'message'=> 'you dont belong here'
        ], 401);
        return $next($request);
    }
}
