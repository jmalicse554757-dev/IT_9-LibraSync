<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && !Auth::user()->isActive()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'login' => 'Your account is no longer active.',
            ]);
        }

        return $next($request);
    }
}