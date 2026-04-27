<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminKeyMiddleware
{
    /**
     * Proteksi sederhana untuk panel admin.
     * Akses via: /admin?key=admin123
     *
     * Untuk thesis demo — tidak memerlukan sistem login penuh.
     * Ganti nilai ADMIN_KEY di .env untuk keamanan lebih baik.
     */
    public function handle(Request $request, Closure $next)
    {
        $validKey = config('app.admin_key', 'admin123');

        if ($request->query('key') !== $validKey) {
            abort(403, 'Akses ditolak. Sertakan key yang valid.');
        }

        // Simpan key ke session agar tidak perlu diketik ulang tiap halaman
        session(['admin_key' => $request->query('key')]);

        return $next($request);
    }
}
