<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('StudentMiddleware reached.');
        if (Auth::check()) {
            // Log user role
            Log::debug('User Roles Check:', ['user_id' => Auth::id(), 'roles' => Auth::user()->getRoleNames()]);
        }

        if (Auth::check() && Auth::user()->hasRole('student')) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized Access');
    }

}
