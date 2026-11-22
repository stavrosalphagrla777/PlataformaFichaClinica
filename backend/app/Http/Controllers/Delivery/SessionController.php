<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Support\FourthYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Autenticación web de Laravel: inicio/cierre de sesión, verificación de cuenta activa,
 * identidad y redirección a la vista correspondiente al rol.
 */
class SessionController extends Controller
{
    /** Valida credenciales, crea sesión y redirige por rol. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'login' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255', // compatibilidad con formularios anteriores
            'password' => 'required|string',
        ]);
        $login = trim((string) ($data['login'] ?? $data['email'] ?? ''));
        if ($login === '') {
            throw ValidationException::withMessages(['login' => 'Ingresa tu usuario o correo.']);
        }
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (!Auth::attempt([$field => $login, 'password' => $data['password']])) {
            throw ValidationException::withMessages(['login' => 'Usuario/correo o contraseña incorrectos.']);
        }
        $request->session()->regenerate();
        $user = Auth::user();
        if (!$user->activo
            || (app()->environment('production') && str_ends_with(strtolower($user->email), '@ficha.test'))
            || !$user->hasAnyRole(['administrador','docente','estudiante'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages(['login' => 'Cuenta inactiva o sin rol válido.']);
        }
        if ($user->hasRole('administrador')) {
            return redirect()->intended(route('admin.dashboard'));
        }
        if ($user->hasRole('docente')) {
            return redirect()->intended(route('preview.docente.dashboard'));
        }
        if (FourthYear::isFourthYear($user)) {
            return redirect()->intended(route('preview.estudiante.casos'));
        }
        // 3.º medio también parte en sus casos asignados, sin enlaces sin código de caso.
        return redirect()->intended(route('preview.estudiante.casos'));
    }

    /** Cierra la sesión autenticada e invalida credenciales web temporales. */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('preview.login');
    }
}
