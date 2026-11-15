<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Recuperación web: envío de enlace, presentación del formulario y cambio de clave.
 * Existe flujo Laravel; pendiente configuración y prueba de correo SMTP real.
 */
class PasswordRecoveryController extends Controller
{
    /** Envía notificación de restablecimiento y redirige a confirmación. */
    public function sendLink(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255']);
        Password::sendResetLink($data);
        // No revelar si existe una cuenta para evitar enumeración de usuarios.
        return redirect()->route('preview.confirmacion');
    }

    /** Muestra formulario con token y correo. */
    public function showReset(Request $request, string $token)
    {
        return view('auth.restablecer', ['token' => $token, 'email' => $request->query('email', '')]);
    }

    /** Cambia contraseña validando token y cierra tokens API previos. */
    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
            $user->tokens()->delete();
            event(new PasswordReset($user));
        });
        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => 'El enlace es inválido o expiró. Solicita uno nuevo.']);
        }
        return redirect()->route('preview.login')->with('status', 'Contraseña actualizada. Ahora puedes iniciar sesión.');
    }
}
