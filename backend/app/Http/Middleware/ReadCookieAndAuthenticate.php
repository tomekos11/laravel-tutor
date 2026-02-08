<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ReadCookieAndAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if ($token = $request->cookie('tutor_access_token')) {
            // Ustawienie tokenu w nagłówkach, aby Passport mogło go odczytać
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
    
        return $next($request);
    }
}