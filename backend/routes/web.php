<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// --- AUTENTICACIÓN ---
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [WebController::class, 'login'])->name('login');
});

// Alias para las vistas de Pablo (Autenticación)
Route::get('/recuperar', [WebController::class, 'recuperar'])->name('preview.recuperar');
Route::get('/confirmacion', [WebController::class, 'confirmacion'])->name('preview.confirmacion');
Route::get('/restablecer', [WebController::class, 'restablecer'])->name('preview.restablecer');

// --- MÓDULO ESTUDIANTE ---
Route::prefix('estudiante')->name('estudiante.')->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('/mis-casos', [WebController::class, 'misCasos'])->name('mis-casos');
    Route::get('/casos', [WebController::class, 'casos'])->name('casos');
    Route::get('/ficha/{id}', [WebController::class, 'ficha'])->name('ficha');
    Route::get('/ficha/{id}/admision', [WebController::class, 'admision'])->name('admision');
    Route::get('/ficha/{id}/triage', [WebController::class, 'triage'])->name('triage');
});

// Alias para las vistas de Pablo (Estudiante)
Route::get('/estudiante/casos', [WebController::class, 'casos'])->name('preview.estudiante.casos');
Route::get('/estudiante/admision', [WebController::class, 'admision'])->name('preview.estudiante.admision');
Route::get('/estudiante/triage', [WebController::class, 'triage'])->name('preview.estudiante.triage');
Route::get('/estudiante/caso/{id}', [WebController::class, 'ficha'])->name('preview.estudiante.caso');

// Rutas para Signos Vitales y ABCDE (usando closures para evitar modificar el controlador)
Route::get('/estudiante/signos-vitales', function () {
    return view('estudiante.signos-vitales');
})->name('preview.estudiante.signos-vitales');

Route::get('/estudiante/abcde', function () {
    return view('estudiante.abcde');
})->name('preview.estudiante.abcde');

// --- MÓDULO ADMINISTRADOR ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [WebController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/usuarios', [WebController::class, 'adminUsuarios'])->name('usuarios');
    Route::get('/usuarios/crear', [WebController::class, 'adminUsuariosCrear'])->name('usuarios.crear');
    Route::get('/usuarios/editar/{id}', [WebController::class, 'adminUsuariosEditar'])->name('usuarios.editar');
    Route::get('/roles-niveles', [WebController::class, 'adminRolesNiveles'])->name('roles-niveles');
});

// Alias para las vistas de Pablo (Admin)
Route::get('/admin/usuarios', [WebController::class, 'adminUsuarios'])->name('preview.admin.usuarios');
Route::get('/admin/crear-usuario', [WebController::class, 'adminUsuariosCrear'])->name('preview.admin.crear');
Route::get('/admin/editar-usuario', [WebController::class, 'adminUsuariosEditar'])->name('preview.admin.editar');
Route::get('/admin/roles-niveles', [WebController::class, 'adminRolesNiveles'])->name('preview.admin.roles');