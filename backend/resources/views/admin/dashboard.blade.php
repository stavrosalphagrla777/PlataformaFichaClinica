<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard administrativo | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logotipo TP Enfermería"></div>
            <span>Ficha Clínica TP</span>
        </div>
        <div class="nav-title">Principal</div>
        <a href="{{ route('preview.admin.dashboard') }}" class="nav-link active" aria-current="page">Dashboard</a>
        <div class="nav-title">Administración</div>
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
        <a href="{{ route('preview.admin.historial') }}" class="nav-link" >Historial de actividad</a>
        <div class="nav-title">Funciones docentes</div>
            <a href="#" class="nav-link">Crear casos clínicos</a>
            <a href="#" class="nav-link">Asignación de casos</a>
            <a href="#" class="nav-link">Revisión de fichas</a>
            <a href="#" class="nav-link">Evaluaciones</a>
        <div class="nav-title">Configuración</div>
            <a href="#" class="nav-link">Configuración</a>
            <a href="#" class="nav-link">Cerrar sesión</a>
    </aside>
    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ocultar menú lateral" aria-expanded="true" aria-controls="sidebar">
                    <span></span><span></span><span></span>
                </button>
                <strong>Dashboard administrativo</strong>
            </div>
            <div class="user-info">
                <div><strong>{{ auth()->user()?->name ?? 'Administrador' }}</strong><small>Panel administrativo</small></div>
                <div class="user-avatar"><span>A</span></div>
            </div>
        </header>
        <main class="content">
            <div class="dash-heading">
                <p class="dash-eyebrow">Plataforma educativa · Ficha Clínica TP</p>
                <h1>Panel general del administrador</h1>
                <p class="subtitle">Accede a la gestión de la plataforma y a las herramientas de seguimiento docente.</p>
            </div>
            <section aria-labelledby="stats-title">
                <div class="dash-section-head"><div><h2 id="stats-title">Resumen de usuarios</h2><p>Distribución de cuentas por perfil de acceso.</p></div></div>
                <div class="stats dash-stats">
                    <div class="stat"><span class="stat-label">Total de usuarios</span><span class="stat-value">{{ $totalUsuarios ?? "—" }}</span><span class="dash-stat-note">Cuentas registradas</span></div>
                    <div class="stat"><span class="stat-label">Estudiantes</span><span class="stat-value">{{ $totalEstudiantes ?? "—" }}</span><span class="dash-stat-note">3.º y 4.º medio</span></div>
                    <div class="stat"><span class="stat-label">Docentes</span><span class="stat-value">{{ $totalDocentes ?? "—" }}</span><span class="dash-stat-note">Personal docente</span></div>
                    <div class="stat"><span class="stat-label">Administradores</span><span class="stat-value">{{ $totalAdministradores ?? "—" }}</span><span class="dash-stat-note">Acceso administrativo</span></div>
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
                                {{-- El controlador puede proporcionar $usuariosRecientes con las cuentas reales. --}}
                                @forelse(($usuariosRecientes ?? []) as $usuario)
                                    @php
                                        $nombreUsuario = $usuario->name ?? $usuario->nombre ?? 'Usuario';
                                        $rolUsuario = $usuario->rol ?? $usuario->role ?? '—';
                                        $nivelUsuario = $usuario->nivel ?? $usuario->nivel_educativo ?? null;
                                        $cursoUsuario = $usuario->curso ?? null;
                                        $estadoUsuario = $usuario->estado ?? 'Activo';
                                        $activoUsuario = in_array(mb_strtolower((string)$estadoUsuario), ['activo', 'active', '1'], true);
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $nombreUsuario }}</strong></td>
                                        <td>{{ $rolUsuario }}</td>
                                        <td>{{ trim(($nivelUsuario ?? '') . ' ' . ($cursoUsuario ?? '')) ?: '—' }}</td>
                                        <td><span class="dash-status {{ $activoUsuario ? 'is-active' : 'is-inactive' }}">{{ $estadoUsuario }}</span></td>
                                        <td><a class="dash-edit" href="{{ route('preview.admin.editar', ['id' => $usuario->id]) }}">Editar</a></td>
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
                        <div class="dash-action-link dash-action-static"><span class="dash-action-icon">◷</span><span><strong>Consultar actividad</strong><small>Revisar historial de acceso</small></span></div>
                    </div>
                </div>
            </section>
            <section class="dash-section" aria-labelledby="docente-title">
                <div class="dash-section-head"><div><h2 id="docente-title">Funciones docentes</h2><p>Gestión académica, casos clínicos y evaluación de estudiantes.</p></div></div>
                <div class="dash-grid">
                    <article class="dash-tile"><div class="dash-icon">C</div><h3>Casos clínicos</h3><p>Crear y administrar casos para 3.º y 4.º medio, diferenciando tareas, exámenes y material de apoyo.</p></article>
                    <article class="dash-tile"><div class="dash-icon">A</div><h3>Asignación de casos</h3><p>Asignar actividades por curso y nivel educativo, con plazos para su realización.</p></article>
                    <article class="dash-tile"><div class="dash-icon">F</div><h3>Revisión de fichas</h3><p>Consultar los registros clínicos desarrollados por estudiantes y revisar su avance.</p></article>
                    <article class="dash-tile"><div class="dash-icon">E</div><h3>Evaluaciones</h3><p>Revisar criterios de rúbrica, porcentaje de logro, calificación y retroalimentación.</p></article>
                    <article class="dash-tile"><div class="dash-icon">%</div><h3>Seguimiento académico</h3><p>Consultar el progreso de las actividades y los resultados de evaluación.</p></article>
                    <article class="dash-tile"><div class="dash-icon">H</div><h3>Historial de actividad</h3><p>Consulta de eventos y cambios realizados en la plataforma.</p></article>
                </div>
            </section>
            <section class="dash-bottom" aria-label="Organización académica">
                <div class="panel dash-info"><h2>Jornadas y cursos</h2><p>Organiza a los estudiantes por jornada <strong>diurna y vespertina</strong>, nivel educativo y curso.</p><p>Administra la distribución de cuentas y el acceso a las actividades de 3.º y 4.º medio.</p></div>
                <div class="panel dash-info"><h2>Seguimiento y evaluación</h2><p>Supervisa el desarrollo de casos clínicos, las fichas registradas y los resultados académicos.</p><p>Consulta las rúbricas, calificaciones y retroalimentaciones de las actividades.</p></div>
            </section>
        </main>
    </div>
</div>
<script>
    const app = document.querySelector('.app');
    const menuToggle = document.getElementById('menuToggle');
    menuToggle.addEventListener('click', () => {
        const hidden = app.classList.toggle('sidebar-hidden');
        menuToggle.setAttribute('aria-expanded', String(!hidden));
        menuToggle.setAttribute('aria-label', hidden ? 'Mostrar menú lateral' : 'Ocultar menú lateral');
    });
</script>
</body>
</html>
