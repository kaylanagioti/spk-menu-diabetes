<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Proteksi panel admin berbasis session.
 * Admin harus login via /admin/login terlebih dahulu.
 */
class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('admin_logged_in')) {
            return redirect()->route('admin.login.form')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
