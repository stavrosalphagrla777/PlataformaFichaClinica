<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
=======
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
<<<<<<< HEAD
    /** HU-01 - Iniciar sesión */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Correo o contraseña incorrectos.'],
            ]);
        }

        if (! $user->activo) {
            return response()->json([
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ], 403);
        }

        $user->load('roles');

        if ($user->roles->isEmpty()) {
            return response()->json([
                'message' => 'Tu cuenta no tiene un rol asignado. Contacta al administrador.',
            ], 403);
        }

=======
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
<<<<<<< HEAD
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('roles'));
    }

    /** HU-02 - Solicitar enlace de recuperación */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // No revelamos si el correo existe o no (evita enumeración de usuarios)
        Password::sendResetLink($request->only('email'));

        return response()->json([
            'message' => 'Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.',
        ]);
    }

    /** HU-02 - Restablecer contraseña con el token del correo */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])
                    ->setRememberToken(Str::random(60));
                $user->save();

                // Cierra todas las sesiones abiertas
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => ['El enlace es inválido o ha expirado. Solicita uno nuevo.'],
            ]);
        }

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }
=======
            'user' => $user->load('roles'),
        ]);
    }
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
}
