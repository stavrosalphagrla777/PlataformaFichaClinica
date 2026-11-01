<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
# Login
Route::get('/login', function () {
    return view('auth.login');
})->name('preview.login');

Route::get('/recuperar', function () {
    return view('auth.recuperar');
})->name('preview.recuperar');

Route::get('/confirmacion', function () {
    return view('auth.confirmacion');
})->name('preview.confirmacion');

Route::get('/restablecer', function () {
    return view('auth.restablecer');
})->name('preview.restablecer');

# Admin
Route::get('/admin/usuarios', function () {
    return view('admin.usuarios.index');
})->name('preview.admin.usuarios');

Route::get('/admin/crear-usuario', function () {
    return view('admin.usuarios.crear');
})->name('preview.admin.crear');

Route::get('/admin/editar-usuario', function () {
    return view('admin.usuarios.editar');
})->name('preview.admin.editar');

Route::get('/admin/roles-niveles', function () {
    return view('admin.roles-niveles');
})->name('preview.admin.roles');

# Estudiantes

Route::get('/estudiante/casos', function () {
    return view('estudiante.casos');
})->name('preview.estudiante.casos');

Route::get('/estudiante/caso/{id}', function ($id) {
    return view('estudiante.detalle-caso', compact('id'));
})->name('preview.estudiante.caso');

# Formularios
Route::get('/estudiante/admision', function () {
    return view('estudiante.admision');
})->name('preview.estudiante.admision');

Route::get('/estudiante/triage', function () {
    return view('estudiante.triage');
})->name('preview.estudiante.triage');
