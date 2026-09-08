<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $isAdmin = $user->role === 'admin' || $user->is_admin === true;

            // Redirect berdasarkan role
            if ($isAdmin) {
                return redirect()->route('admin.aula.index');
            }

            return redirect()->route('booking.index');
        }

        return back()->withErrors([
            'login' => 'Email/Username atau password salah.',
        ])->onlyInput('login');
    }

    /**
     * Get credentials dari input (email atau username)
     */
    protected function getCredentials(LoginRequest $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil log out!');
    }
}