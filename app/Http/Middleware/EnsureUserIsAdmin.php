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
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai Administrator terlebih dahulu.');
        }

        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh Administrator.');
        }

        if (!Auth::guard('admin')->check()) {
            Auth::guard('admin')->login($user);
        }

        return $next($request);
    }
}
