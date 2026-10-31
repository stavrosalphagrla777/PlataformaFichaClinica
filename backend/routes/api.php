<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', function() { return response()->json(['message' => 'ok']); });
Route::post('/auth/reset-password', function() { return response()->json(['message' => 'ok']); });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    Route::get('/admin/users', function() { return response()->json(['data' => []]); })->middleware('role:administrador');
});

Route::middleware('auth:sanctum')->post('/fichas/{id}/triage', function() { return response()->json(['message' => 'ok']); });
