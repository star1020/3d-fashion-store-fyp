<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
            if(Auth::user()->role == 'staff' || Auth::user()->role == 'admin'){
                return $next($request);
            } else {
                return response()->view('401', [], 401);
            }
        } else {
            return redirect('/')->with('message', 'Login to access the website info');
        }

        return $next($request);
    }
}

