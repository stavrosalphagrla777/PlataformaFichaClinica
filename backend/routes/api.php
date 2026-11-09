<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdmisionController;
use App\Http\Controllers\Api\TriageController;
use App\Http\Controllers\Api\SignosVitalesController;
use App\Http\Controllers\Api\EvaluacionAbcdeController;
use App\Http\Controllers\Api\UserManagementController;

// --- RUTAS PÚBLICAS ---
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [\App\Http\Controllers\Api\PasswordResetController::class, 'resetPassword']);

// --- RUTAS PROTEGIDAS (Requieren token Sanctum) ---
Route::middleware('auth.token')->group(function () {
    
    // Autenticación
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Admisión (Crear Ficha)
    Route::post('/fichas', [AdmisionController::class, 'store']);

    // Triage
    Route::post('/fichas/{id}/triage', [TriageController::class, 'store']);

    // Signos Vitales (Ahora dentro del grupo protegido)
    Route::post('/fichas/{id}/signos-vitales', [SignosVitalesController::class, 'store']);

    // Evaluación ABCDE
    Route::post('/fichas/{id}/evaluacion-abcde', [EvaluacionAbcdeController::class, 'store']);

    // Administración de Usuarios (Solo rol administrador)
    Route::middleware('role:administrador')->group(function () {
        Route::get('/admin/users', [UserManagementController::class, 'index']);
        Route::post('/admin/users', [UserManagementController::class, 'store']);
        Route::put('/admin/users/{user}', [UserManagementController::class, 'update']);
        Route::patch('/admin/users/{user}/desactivar', [UserManagementController::class, 'desactivar']);
        Route::patch('/admin/users/{user}/activar', [UserManagementController::class, 'activar']);
        Route::patch('/admin/users/{user}/rol-nivel', [UserManagementController::class, 'asignarRolNivel']);
    });
});