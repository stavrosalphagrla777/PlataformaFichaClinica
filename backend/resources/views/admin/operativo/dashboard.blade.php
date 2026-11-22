@extends('admin.operativo.layout')
@section('title','Panel general')
@section('content')

            <div class="dash-heading">
                <p class="dash-eyebrow">Plataforma educativa · Ficha Clínica TP</p>
                <h1>Panel general del administrador</h1>
                <p class="subtitle">Accede a la gestión de la plataforma y a las herramientas de seguimiento docente.</p>
            </div>
            <section aria-labelledby="stats-title">
                <div class="dash-section-head"><div><h2 id="stats-title">Resumen de usuarios</h2><p>Distribución de cuentas por perfil de acceso.</p></div></div>
                <div class="stats dash-stats">
                    <div class="stat"><span class="stat-label">Total de usuarios</span><span class="stat-value">{{ $counts['usuarios'] ?? 0 }}</span><span class="dash-stat-note">Cuentas registradas</span></div>
                    <div class="stat"><span class="stat-label">Estudiantes</span><span class="stat-value">{{ $counts['estudiantes'] ?? 0 }}</span><span class="dash-stat-note">3.º y 4.º medio</span></div>
                    <div class="stat"><span class="stat-label">Docentes</span><span class="stat-value">{{ $counts['docentes'] ?? 0 }}</span><span class="dash-stat-note">Personal docente</span></div>
                    <div class="stat"><span class="stat-label">Administradores</span><span class="stat-value">{{ $counts['administradores'] ?? 0 }}</span><span class="dash-stat-note">Acceso administrativo</span></div>
                </div>
            </section>
            <section class="dash-main-grid" aria-label="Usuarios recientes y accesos rápidos">
                <div class="panel dash-users-panel">
                    <div class="dash-panel-header">
                        <div><h2>Usuarios recientes</h2><p>Vista general de las cuentas de la plataforma</p></div>
                        <a class="dash-view-all" href="{{ route('preview.admin.usuarios') }}">Ver usuarios</a>
                    </div>
                    <div class="dash-table-wrap">
                        <table class="dash-users-table">
                            <thead><tr><th>Nombre</th><th>Rol</th><th>Nivel / curso</th><th>Estado</th><th>Acción</th></tr></thead>
                            <tbody>
                                
                                @forelse(($recent ?? []) as $usuario)
                                    <tr>
                                        <td><strong>{{ $usuario->name }}</strong></td>
                                        <td>{{ $usuario->getRoleNames()->implode(', ') }}</td>
                                        <td>{{ trim(($usuario->nivel_educativo ?? '') . ' ' . ($usuario->curso ?? '')) ?: '—' }}</td>
                                        <td><span class="dash-status {{ $usuario->activo ? 'is-active' : 'is-inactive' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span></td>
                                        <td><a class="dash-edit" href="{{ route('admin.usuarios.editar', $usuario) }}">Editar</a></td>
                                    </tr>
                                @empty
                                    <tr><td class="dash-empty" colspan="5">No hay usuarios recientes para mostrar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel dash-actions-panel">
                    <div class="dash-panel-header"><div><h2>Accesos rápidos</h2><p>Operaciones del administrador</p></div></div>
                    <div class="dash-actions-list">
                        <a class="dash-action-link" href="{{ route('preview.admin.usuarios') }}"><span class="dash-action-icon">›</span><span><strong>Gestionar usuarios</strong><small>Buscar, editar y desactivar cuentas</small></span><span class="dash-chevron">›</span></a>
                        <a class="dash-action-link" href="{{ route('preview.admin.crear') }}"><span class="dash-action-icon">+</span><span><strong>Crear usuario</strong><small>Registrar estudiantes y docentes</small></span><span class="dash-chevron">›</span></a>
                        <a class="dash-action-link" href="{{ route('preview.admin.roles') }}"><span class="dash-action-icon">✓</span><span><strong>Asignar rol y nivel</strong><small>Configurar permisos y nivel educativo</small></span><span class="dash-chevron">›</span></a>
                        <a class="dash-action-link" href="{{ route('admin.historial') }}"><span class="dash-action-icon">◷</span><span><strong>Consultar actividad</strong><small>Revisar cambios de usuarios</small></span></a>
                    </div>
                </div>
            </section>
            <section class="dash-section" aria-labelledby="docente-title">
                <div class="dash-section-head"><div><h2 id="docente-title">Funciones docentes</h2><p>Gestión académica, casos clínicos y evaluación de estudiantes.</p></div></div>
                <div class="dash-grid">
                    <article class="dash-tile"><div class="dash-icon">C</div><h3>Casos clínicos</h3><p>Crear y administrar casos para 3.º y 4.º medio, diferenciando tareas, exámenes y material de apoyo.</p><a href="{{ route('preview.docente.casos') }}">Abrir módulo</a></article>
                    <article class="dash-tile"><div class="dash-icon">A</div><h3>Asignación de casos</h3><p>Asignar actividades por curso y nivel educativo, con plazos para su realización.</p><a href="{{ route('preview.docente.asignaciones') }}">Abrir módulo</a></article>
                    <article class="dash-tile"><div class="dash-icon">F</div><h3>Revisión de fichas</h3><p>Consultar los registros clínicos desarrollados por estudiantes y revisar su avance.</p><a href="{{ route('preview.docente.revision-fichas') }}">Abrir módulo</a></article>
                    <article class="dash-tile"><div class="dash-icon">E</div><h3>Evaluaciones</h3><p>Revisar criterios de rúbrica, porcentaje de logro, calificación y retroalimentación.</p><a href="{{ route('preview.docente.evaluaciones') }}">Abrir módulo</a></article>
                    <article class="dash-tile"><div class="dash-icon">%</div><h3>Seguimiento académico</h3><p>Consultar el progreso de las actividades y los resultados de evaluación.</p><a href="{{ route('preview.docente.dashboard') }}">Abrir módulo</a></article>
                    <article class="dash-tile"><div class="dash-icon">H</div><h3>Historial de actividad</h3><p>Consulta de eventos y cambios realizados en la plataforma.</p><a href="{{ route('admin.historial') }}">Abrir módulo</a></article>
                </div>
            </section>
            <section class="dash-bottom" aria-label="Organización académica">
                <div class="panel dash-info"><h2>Jornadas y cursos</h2><p>Organiza a los estudiantes por jornada <strong>diurna y vespertina</strong>, nivel educativo y curso.</p><p>Administra la distribución de cuentas y el acceso a las actividades de 3.º y 4.º medio.</p></div>
                <div class="panel dash-info"><h2>Seguimiento y evaluación</h2><p>Supervisa el desarrollo de casos clínicos, las fichas registradas y los resultados académicos.</p><p>Consulta las rúbricas, calificaciones y retroalimentaciones de las actividades.</p></div>
            </section>
        
@endsection
