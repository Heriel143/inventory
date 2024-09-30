<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if the JWT token exists in cookies
            $token = $request->cookie('jwt_token');
            // dd($token);
            if (!$token) {
                // If no token in cookies, redirect to login
                return redirect()->route('login')->with('error', 'You must be logged in to access the dashboard.');
            }

            // Manually set the token for JWTAuth
            JWTAuth::setToken($token);

            // Try to authenticate the user using the token
            if (!JWTAuth::authenticate()) {
                return redirect()->route('login')->with('error', 'User not found.');
            }
        } catch (JWTException) {
            // Token is invalid or expired
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // Token is valid, allow the request to proceed
        return $next($request);
    }
}
