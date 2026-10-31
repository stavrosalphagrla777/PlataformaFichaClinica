<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/fichas', [\App\Http\Controllers\Api\AdmisionController::class, 'store']);
    
        // Rutas de administración de usuarios
    Route::middleware('role:administrador')->group(function () {
        Route::get('/admin/users', [\App\Http\Controllers\Api\UserManagementController::class, 'index']);
        Route::post('/admin/users', [\App\Http\Controllers\Api\UserManagementController::class, 'store']);
        Route::put('/admin/users/{user}', [\App\Http\Controllers\Api\UserManagementController::class, 'update']);
        Route::patch('/admin/users/{user}/desactivar', [\App\Http\Controllers\Api\UserManagementController::class, 'desactivar']);
        Route::patch('/admin/users/{user}/activar', [\App\Http\Controllers\Api\UserManagementController::class, 'activar']);
        Route::patch('/admin/users/{user}/rol-nivel', [\App\Http\Controllers\Api\UserManagementController::class, 'asignarRolNivel']);
    });
});

Route::middleware('auth:sanctum')->post('/fichas/{id}/triage', function() { return response()->json(['message' => 'ok']); });

