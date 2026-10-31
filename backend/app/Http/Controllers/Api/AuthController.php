<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
