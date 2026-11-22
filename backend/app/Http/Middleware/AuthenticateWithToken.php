<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token no proporcionado'], 401);
        }

        // 🚀 Usar findToken con el token completo (incluye el prefijo)
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user = $accessToken->tokenable;
        if (!$user || !$user->activo || $user->roles->isEmpty()
            || (app()->environment('production') && str_ends_with(strtolower($user->email), '@ficha.test'))) {
            return response()->json(['message' => 'Cuenta no habilitada para acceder'], 403);
        }

        $user->withAccessToken($accessToken);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}