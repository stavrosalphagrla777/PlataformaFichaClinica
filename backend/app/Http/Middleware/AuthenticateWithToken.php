<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado'
            ], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'message' => 'Token inválido'
            ], 401);
        }

        $user = $accessToken->tokenable;

        if (
            !$user ||
            !$user->activo ||
            $user->roles->isEmpty() ||
            (
                app()->environment('production') &&
                str_ends_with(strtolower($user->email), '@ficha.test')
            )
        ) {
            return response()->json([
                'message' => 'Cuenta no habilitada para acceder'
            ], 403);
        }

        // Asociar el token al usuario autenticado.
        $user->withAccessToken($accessToken);

        // Registrar al usuario en el guard web para que
        // Laravel y Spatie Permission puedan reconocerlo.
        Auth::guard('web')->setUser($user);
        Auth::shouldUse('web');

        // Mantener disponible el usuario en la petición.
        $request->setUserResolver(fn ($guard = null) => $user);

        return $next($request);
    }
}
