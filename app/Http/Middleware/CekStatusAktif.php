<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekStatusAktif
{
    /**
     * Cegah user yang sudah login tapi statusnya sudah dinonaktifkan
     * untuk mengakses halaman apapun.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status === 'nonaktif') {
            // Logout paksa
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect ke halaman "akun nonaktif"
            return redirect()->route('akun.nonaktif');
        }

        return $next($request);
    }
}