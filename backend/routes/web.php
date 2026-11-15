<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\PasswordRecoveryController;
use App\Http\Controllers\Delivery\SessionController;
use App\Http\Controllers\Delivery\TeacherController;
use App\Http\Controllers\Delivery\StudentCaseController;
use App\Http\Controllers\Delivery\ReviewController;
use Illuminate\Http\Request;

// Ruta de bienvenida
Route::get('/', fn () => redirect()->route('preview.login'));

// --- AUTENTICACIÓN ---
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [WebController::class, 'login'])->name('login');
});

// Alias para las vistas de Pablo (Autenticación)
Route::get('/recuperar', [WebController::class, 'recuperar'])->name('preview.recuperar');
Route::get('/confirmacion', [WebController::class, 'confirmacion'])->name('preview.confirmacion');
Route::get('/restablecer', fn () => redirect()->route('preview.recuperar'))->name('preview.restablecer');
Route::post('/recuperar', [PasswordRecoveryController::class, 'sendLink'])->middleware('throttle:5,1')->name('password.email');
Route::get('/restablecer/{token}', [PasswordRecoveryController::class, 'showReset'])->name('password.reset');
Route::post('/restablecer', [PasswordRecoveryController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');

// PDF de pautas finalizadas: estudiantes descargan las suyas; docentes las asignadas; administración todas.
Route::middleware('auth')->get('/evaluaciones/{record}/pdf',
    [\App\Http\Controllers\Delivery\EvaluationPdfController::class, 'download'])
    ->name('delivery.evaluacion.pdf');

// --- ESTUDIANTES: FLUJO REAL DE 4.º MEDIO ---
Route::middleware(['auth', 'role:estudiante'])->group(function () {
    Route::get('/estudiante/dashboard', [StudentCaseController::class, 'index'])->name('estudiante.dashboard');
    Route::get('/estudiante/mis-casos', [StudentCaseController::class, 'index'])->name('estudiante.mis-casos');
    Route::get('/estudiante/casos', [StudentCaseController::class, 'index'])->name('preview.estudiante.casos');
    Route::get('/estudiante/caso/{id}', [StudentCaseController::class, 'show'])->name('preview.estudiante.caso');
    Route::get('/estudiante/ficha/{id}', [StudentCaseController::class, 'show'])->name('estudiante.ficha');
    Route::get('/estudiante/ficha/{id}/admision', fn (string $id) => redirect()->route('preview.estudiante.admision', ['caso' => $id]))->name('estudiante.admision');
    Route::get('/estudiante/ficha/{id}/triage', fn (string $id) => redirect()->route('preview.estudiante.triage', ['caso' => $id]))->name('estudiante.triage');
});

// Todos los formularios del 4.º medio requieren sesión, rol, asignación y plazo.
Route::middleware(['auth', 'role:estudiante', '4to.case'])
    ->prefix('estudiante')->name('preview.estudiante.')->group(function () {
        Route::get('/admision', fn () => view('estudiante.admision'))->name('admision');
        Route::get('/triage', fn () => view('estudiante.triage'))->name('triage');
        Route::get('/signos-vitales', fn () => view('estudiante.signos-vitales'))->name('signos-vitales');
        Route::get('/abcde', fn () => view('estudiante.abcde'))->name('abcde');
        Route::get('/anamnesis', fn () => view('estudiante.anamnesis'))->name('anamnesis');
        Route::get('/examen-fisico', fn () => view('estudiante.examen-fisico'))->name('examen-fisico');
        Route::get('/procedimientos', fn () => view('estudiante.procedimientos'))->name('procedimientos');
        Route::get('/medicamentos_4to', fn () => view('estudiante.medicamentos_4to'))->name('medicamentos_4to');
        Route::get('/examenes', fn () => view('estudiante.examenes'))->name('examenes');
        Route::get('/acciones-enfermeria', fn () => view('estudiante.acciones-enfermeria'))->name('acciones-enfermeria');
        Route::get('/destino', fn () => view('estudiante.destino'))->name('destino');
    });

// AJAX de formularios de 4.º medio; CSRF de la sesión web (no tokens en localStorage).
Route::middleware(['auth', 'role:estudiante'])->prefix('4to/casos/{code}')
    ->name('delivery.student.')->group(function () {
        Route::get('/secciones', [StudentCaseController::class, 'snapshots'])->name('snapshots');
        Route::put('/secciones/{section}', [StudentCaseController::class, 'saveSection'])->name('save');
        Route::post('/entregar', [StudentCaseController::class, 'submit'])->name('submit');
        Route::post('/secciones/{section}/upload', [StudentCaseController::class, 'uploadExamFile'])->name('upload');
    });


// --- ADMINISTRACIÓN: sesión y base de datos ---
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'users'])->name('admin.usuarios');
    Route::get('/usuarios/crear', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'create'])->name('admin.usuarios.crear');
    Route::post('/usuarios', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'store'])->name('admin.usuarios.store');
    Route::post('/usuarios/lote', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'bulkStatus'])->name('admin.usuarios.lote');
    Route::post('/usuarios/importar', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'importUsers'])->name('admin.usuarios.importar');
    Route::post('/roles-niveles/lote', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'bulkRoles'])->name('admin.roles-niveles.lote');
    Route::get('/usuarios/{user}/editar', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'edit'])->name('admin.usuarios.editar');
    Route::put('/usuarios/{user}', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'update'])->name('admin.usuarios.update');
    Route::patch('/usuarios/{user}/estado', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'status'])->name('admin.usuarios.estado');
    Route::get('/roles-niveles', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'roles'])->name('admin.roles-niveles');
    Route::get('/historial', [\App\Http\Controllers\Delivery\AdminPanelController::class, 'history'])->name('admin.historial');
});
// Compatibilidad con enlaces existentes del frontend de Pablo.
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::redirect('/admin/crear-usuario', '/admin/usuarios/crear')->name('preview.admin.crear');
    Route::get('/admin/editar-usuario', fn () => redirect()->route('admin.usuarios'))->name('preview.admin.editar');
    Route::redirect('/admin/dashboard-panel', '/admin/dashboard')->name('preview.admin.dashboard');
    Route::redirect('/admin/historial-actividad', '/admin/historial')->name('preview.admin.historial');
    Route::redirect('/admin/roles', '/admin/roles-niveles')->name('preview.admin.roles');
    Route::redirect('/admin/listado', '/admin/usuarios')->name('preview.admin.usuarios');
});

// === INTEGRACIÓN FRONTEND PABLO / BACKEND ALEXIS (FASE 1) ===
// Mantener endpoints y controladores de Alexis; reincorporar rutas visuales de Pablo.
// 4.º medio y docentes: primera integración persistente con autenticación web.
// 3.º medio conserva las rutas de previsualización originales.

Route::get('/login', [WebController::class, 'login'])->name('preview.login');
Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:10,1')->name('delivery.login.submit');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('delivery.logout');
Route::get('/iniciar-sesion', [WebController::class, 'login'])->name('login');

// Docentes: flujo funcional respaldado por base de datos.
Route::middleware(['auth', 'role:docente|administrador'])->prefix('docente')->name('preview.docente.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/casos', [TeacherController::class, 'index'])->name('casos');
    Route::get('/crear-caso', [TeacherController::class, 'create'])->name('crear-caso');
    Route::post('/casos', [TeacherController::class, 'store'])->name('store-caso');
    Route::delete('/casos/{case}', [TeacherController::class, 'destroy'])->name('casos.destroy');
    Route::get('/asignaciones', [TeacherController::class, 'assignments'])->name('asignaciones');
    Route::get('/asignaciones/datos', [TeacherController::class, 'assignmentIndex'])->name('assignments.index');
    Route::post('/asignaciones', [TeacherController::class, 'assignmentStore'])->name('assignments.store');
    Route::put('/asignaciones/{assignment}', [TeacherController::class, 'assignmentUpdate'])->name('assignments.update');
    Route::delete('/asignaciones/{assignment}', [TeacherController::class, 'assignmentDestroy'])->name('assignments.destroy');
    Route::get('/revision-fichas', [TeacherController::class, 'review'])->name('revision-fichas');
    Route::get('/evaluaciones', [TeacherController::class, 'evaluations'])->name('evaluaciones');
    Route::get('/revision-3ro', fn () => redirect()->route('preview.docente.evaluaciones', ['nivel' => '3']))->name('revision-3ro');
    Route::get('/revision-3ro/{record}', [\App\Http\Controllers\Delivery\ThirdReviewController::class, 'show'])->name('revision-3ro.detalle');
    Route::post('/revision-3ro/{record}/evaluar', [\App\Http\Controllers\Delivery\ThirdReviewController::class, 'feedback'])->name('revision-3ro.evaluar');
    Route::get('/fichas/datos', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/fichas/{record}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/fichas/{record}/evaluacion', [ReviewController::class, 'evaluate'])->name('reviews.evaluate');
});

// 3.º medio: vistas y guardado MySQL únicamente para casos asignados al estudiante.
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante/3ro')->name('preview.estudiante.3ro.')->group(function () {
    foreach (['antecedentes-3ro','signos-vitales-3ro','examen-fisico-3ro','citaciones-3ro'] as $slug) {
        Route::get('/'.$slug, fn (Request $request) => app(\App\Http\Controllers\Delivery\ThirdYearController::class)->show($request,$slug))->name($slug);
    }
});
Route::middleware(['auth','role:estudiante'])->prefix('3ro/casos/{code}')->name('delivery.third.')->group(function () {
    Route::put('/secciones/{section}', [\App\Http\Controllers\Delivery\ThirdYearController::class,'save'])->name('save');
    Route::post('/entregar', [\App\Http\Controllers\Delivery\ThirdYearController::class,'submit'])->name('submit');
});
