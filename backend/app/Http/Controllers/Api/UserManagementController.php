<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * API de administración de cuentas, estado y roles para clientes con token.
 * La ruta exige rol administrador; mantener alineada con reglas del panel web.
 */
class UserManagementController extends Controller
{
    /** Lista usuarios con filtros y paginación mediante API. */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('rol')) {
            $query->role($request->rol);
        }
        if ($request->has('nivel_educativo')) {
            $query->where('nivel_educativo', $request->nivel_educativo);
        }
        if ($request->has('activo')) {
            $query->where('activo', $request->activo);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('rut', 'like', "%{$search}%");
            });
        }

        $users = $query->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ];
        });

        return response()->json(['data' => $users]);
    }

    /** Crea cuenta y asigna permisos desde la API. */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'rol' => 'required|exists:roles,name',
            'nivel_educativo' => 'required_if:rol,estudiante',
            'curso' => 'required_if:rol,estudiante',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nivel_educativo' => $request->rol === 'estudiante' ? $request->nivel_educativo : null,
            'curso' => $request->rol === 'estudiante' ? $request->curso : null,
            'activo' => true,
        ]);

        $user->assignRole($request->rol);
        $user->refresh();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ]
        ], 201);
    }

    /** Edita una cuenta existente. */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));
        $user->refresh();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ]
        ]);
    }

    /** Inhabilita la cuenta y previene nuevos accesos. */
    public function desactivar(User $user, Request $request)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes desactivar tu propia cuenta.'], 422);
        }
        $user->update(['activo' => false]);
        $user->tokens()->delete();
        $user->refresh();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ]
        ]);
    }

    /** Habilita una cuenta desactivada. */
    public function activar(User $user)
    {
        $user->update(['activo' => true]);
        $user->refresh();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ]
        ]);
    }

    /** Actualiza rol, nivel educativo y curso. */
    public function asignarRolNivel(Request $request, User $user)
    {
        $request->validate([
            'rol' => 'required|exists:roles,name',
            'nivel_educativo' => 'required_if:rol,estudiante',
            'curso' => 'required_if:rol,estudiante',
        ]);

        if ($request->user()->id === $user->id && $request->rol !== 'administrador') {
            return response()->json(['message' => 'No puedes quitarte tu propio rol de administrador.'], 422);
        }

        $user->syncRoles([$request->rol]);
        $user->update([
            'nivel_educativo' => $request->rol === 'estudiante' ? $request->nivel_educativo : null,
            'curso' => $request->rol === 'estudiante' ? $request->curso : null,
        ]);

        $user->refresh();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->getRoleNames()->first(),
                'nivel_educativo' => $user->nivel_educativo,
                'curso' => $user->curso,
                'activo' => (bool) $user->activo,
            ]
        ]);
    }
}