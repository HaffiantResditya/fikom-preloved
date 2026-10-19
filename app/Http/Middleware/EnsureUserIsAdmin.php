<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check() || !Auth::guard('admin')->user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Silakan login sebagai Administrator terlebih dahulu.');
        }

        return $next($request);
    }
}
