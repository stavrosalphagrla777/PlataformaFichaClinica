<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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