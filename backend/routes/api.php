<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::post('/auth/login', function() { return response()->json(['token' => 'fake']); });
Route::post('/auth/logout', function() { return response()->json(['message' => 'ok']); });
Route::post('/auth/forgot-password', function() { return response()->json(['message' => 'ok']); });
Route::post('/auth/reset-password', function() { return response()->json(['message' => 'ok']); });
Route::get('/auth/me', function(Request $request) { return $request->user(); })->middleware('auth:sanctum');
Route::get('/admin/users', function() { return response()->json(['data' => []]); })->middleware('auth:sanctum');
=======
use App\Http\Controllers\TriageController;
use App\Http\Controllers\SignosVitalesController;
use App\Http\Controllers\EvaluacionAbcdeController;

/*
|--------------------------------------------------------------------------
| Rutas API - Plataforma Ficha Clínica TP
|--------------------------------------------------------------------------
*/

Route::prefix('fichas/{medicalRecordId}')->group(function () {
    // 1. Triage (3.º y 4.º Medio) 
    Route::post('/triage', [TriageController::class, 'store']);
    Route::get('/triage', [TriageController::class, 'show']);

    // 2. Signos Vitales e IMC (3.º Medio) 📊
    Route::post('/signos-vitales', [SignosVitalesController::class, 'store']);
    Route::get('/signos-vitales', [SignosVitalesController::class, 'index']);

    // 3. Evaluación Primaria ABCDE (4.º Medio) 🩺
    Route::post('/evaluacion-abcde', [EvaluacionAbcdeController::class, 'store']);
    Route::get('/evaluacion-abcde', [EvaluacionAbcdeController::class, 'show']);
});
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
