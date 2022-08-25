<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NonAuth
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
        $jwt = $request->cookie('jwt');

        if($jwt = $request->cookie('jwt'))
        {
            abort(401,'Your already logged in. To get access this resouce logout first.');
        }

        return $next($request);
    }
}
