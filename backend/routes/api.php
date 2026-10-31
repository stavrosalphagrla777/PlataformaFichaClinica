<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', function() { return response()->json(['message' => 'ok']); }); // Placeholder
Route::post('/auth/reset-password', function() { return response()->json(['message' => 'ok']); }); // Placeholder

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Rutas de admin (placeholder)
    Route::get('/admin/users', function() { return response()->json(['data' => []]); })->middleware('role:administrador');
});

// Ruta de fichas (placeholder)
Route::middleware('auth:sanctum')->post('/fichas/{id}/triage', function() { return response()->json(['message' => 'ok']); });
