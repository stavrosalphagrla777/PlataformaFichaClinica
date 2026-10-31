<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;

// Ruta de bienvenida por defecto
Route::get('/', function () {
    return view('welcome');
});

// Rutas del estudiante (Temporalmente sin middleware de autenticación web)
Route::prefix('estudiante')->group(function () {
    // Pantalla principal: Mis Casos Clínicos
    Route::get('/mis-casos', [WebController::class, 'misCasos'])->name('estudiante.mis-casos');
    
    // Pantalla de Admisión (le pasamos un ID de prueba)
    Route::get('/admision/{id}', [WebController::class, 'admision'])->name('estudiante.admision');
});
