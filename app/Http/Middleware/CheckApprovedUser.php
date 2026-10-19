<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isAdmin()) {
            if (Auth::user()->isSuspended()) {
                if (!$request->routeIs('suspended.notice') && !$request->routeIs('logout')) {
                    return redirect()->route('suspended.notice');
                }
            } elseif (!Auth::user()->isApproved()) {
                if (!$request->routeIs('pending.notice') && !$request->routeIs('logout')) {
                    return redirect()->route('pending.notice');
                }
            }
        }

        return $next($request);
    }
}
