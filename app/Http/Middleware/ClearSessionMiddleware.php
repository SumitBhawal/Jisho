<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;


class ClearSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        //To check if this is the first search in the session
        if (!session()->has('session_cleared')) {
            // Clear the session
            Session::flush();
            session()->put('session_cleared', true);
        }

        return $next($request);
    }
}
