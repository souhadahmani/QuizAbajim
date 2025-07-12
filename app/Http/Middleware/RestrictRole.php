<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }

        // Check if the user's role matches the required role
        if (Auth::user()->role_name !== $role) {
            return redirect()->route('dashboard')->with('error', 'ليس لديك الصلاحية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}