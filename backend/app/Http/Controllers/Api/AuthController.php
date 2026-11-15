<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Autenticación de la API heredada de Alexis: tokens Sanctum, usuario autenticado
 * y revocación. Ruta protegida con middleware auth.token cuando corresponde.
 */
class AuthController extends Controller
{
    /** Autentica y emite token API. */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255', // retrocompatibilidad API
            'password' => 'required|string',
        ]);
        $login = trim((string) ($data['login'] ?? $data['email'] ?? ''));
        if ($login === '') {
            throw ValidationException::withMessages(['login' => ['Ingresa usuario o correo.']]);
        }
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $login)->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            $errorField = $request->filled('login') ? 'login' : 'email';
            throw ValidationException::withMessages([
                $errorField => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Las cuentas QA no se podrán utilizar en un entorno público aunque se copie la BD.
        if (app()->environment('production') && str_ends_with(strtolower($user->email), '@ficha.test')) {
            return response()->json(['message' => 'Cuenta de pruebas deshabilitada'], 403);
        }

        if (isset($user->activo) && !$user->activo) {
            return response()->json(['message' => 'Usuario desactivado'], 403);
        }

                // Validar que el usuario tenga un rol asignado
        if ($user->roles->isEmpty()) {
            return response()->json(['message' => 'El usuario no tiene un rol asignado.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
            ]
        ]);
    }

    /** Devuelve los datos de la cuenta autenticada. */
    public function me(Request $request)
    {
        return response()->json([
            'data' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'rol' => $request->user()->getRoleNames()->first(),
                'nivel_educativo' => $request->user()->nivel_educativo,
                'curso' => $request->user()->curso,
            ]
        ]);
    }

    /** Revoca la sesión token utilizada. */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
