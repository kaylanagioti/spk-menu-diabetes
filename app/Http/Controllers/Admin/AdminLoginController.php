<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function showForm()
    {
        // Jika sudah login, langsung ke dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $validPassword = config('app.admin_key', 'admin123');

        if ($request->password !== $validPassword) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        session(['admin_logged_in' => true]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login.form')
            ->with('success', 'Anda telah keluar dari panel admin.');
    }
}
