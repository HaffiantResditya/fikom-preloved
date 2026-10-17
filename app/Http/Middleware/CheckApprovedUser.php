<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovedUser
{
    /**
     * Blokir akses user yang belum disetujui admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isApproved() && !Auth::user()->isAdmin()) {
            return redirect()->route('pending.notice');
        }

        return $next($request);
    }
}
