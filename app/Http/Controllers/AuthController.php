<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogSistema;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        // Check if user exists and is active
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if ($user && !$user->activo) {
            return back()->withErrors([
                'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log the login
            LogSistema::create([
                'usuario_id' => Auth::id(),
                'accion' => 'login',
                'detalle' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log the logout
        if (Auth::check()) {
            LogSistema::create([
                'usuario_id' => Auth::id(),
                'accion' => 'logout',
                'detalle' => [
                    'ip' => $request->ip(),
                ],
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
