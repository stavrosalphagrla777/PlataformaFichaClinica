<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard docente | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ajustes.css') }}">
</head>

<body>
    <div class="app" id="app">
        @include('shared.navigation')
        <div class="main">
            <header class="topbar">
                <div class="topbar-left"><button class="menu-toggle" id="menuToggle" type="button"
                        aria-label="Mostrar u ocultar menú" aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button><strong>Dashboard docente</strong></div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content"><div style="margin:8px 0 16px;display:flex;gap:8px"><a class="btn btn-secondary" href="{{ request()->fullUrlWithQuery(['jornada'=>'diurno']) }}" style="{{ ($jornada??'diurno')==='diurno'?'font-weight:800':'' }}">Diurnos</a><a class="btn btn-secondary" href="{{ request()->fullUrlWithQuery(['jornada'=>'vespertino']) }}" style="{{ ($jornada??'diurno')==='vespertino'?'font-weight:800':'' }}">Vespertinos</a></div>
                <div class="heading">
                    <div>
                        <h1>Panel docente</h1>
                        <p class="subtitle">Seguimiento de simulaciones clínicas y actividades académicas.</p>
                    </div>
                </div>
                @php
                    $resumen = $resumen ?? [];
                    $evaluaciones = $evaluaciones ?? [];
                    $indicadores = $indicadores ?? [];
                @endphp
                <section class="stats" aria-label="Resumen académico">
                    <div class="stat"><span class="stat-label">Estudiantes evaluados</span><strong
                            class="stat-value">{{ $resumen['evaluados'] ?? '—' }}</strong>
                        <div class="stat-note">Evaluaciones registradas</div>
                    </div>
                    <div class="stat"><span class="stat-label">Promedio del curso</span><strong
                            class="stat-value">{{ isset($resumen['promedio']) ? number_format((float) $resumen['promedio'], 1, ',', '.') : '—' }}</strong>
                        <div class="stat-note">Escala aplicada: 2,0 a 7,0</div>
                    </div>
                    <div class="stat"><span class="stat-label">Aprobados</span><strong
                            class="stat-value">{{ $resumen['aprobados'] ?? '—' }}</strong>
                        <div class="stat-note">Cumplimiento desde 60 %</div>
                    </div>
                    <div class="stat"><span class="stat-label">Fichas por revisar</span><strong
                            class="stat-value">{{ $resumen['pendientes'] ?? '—' }}</strong>
                        <div class="stat-note">Revisión docente</div>
                    </div>
                </section>
                <section class="panel" id="fichasEntregadas" style="margin:18px 0">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                        <div><h2>Fichas entregadas por revisar</h2><p class="panel-description">Entregas reales de estudiantes pendientes de evaluación docente.</p></div>
                        <a class="btn btn-secondary" href="{{ route('preview.docente.revision-fichas') }}">Ver todas las fichas</a>
                    </div>
                    <div class="table-wrap"><table class="teacher-table"><thead><tr><th>Estudiante</th><th>Caso</th><th>Estado</th><th>Fecha de entrega</th><th>Acción</th></tr></thead><tbody>
                        @forelse($pendientesDetalle ?? [] as $ficha)
                            <tr><td><strong>{{ $ficha['estudiante'] }}</strong><br><span class="muted">{{ $ficha['curso'] }}</span></td>
                                <td>{{ $ficha['codigo'] }} · {{ $ficha['titulo'] }}</td>
                                <td><span class="pill">{{ $ficha['estado'] }}</span></td><td>{{ $ficha['fecha'] }}</td>
                                <td><a class="btn btn-secondary" href="{{ route('preview.docente.revision-fichas', ['ficha'=>$ficha['id']]) }}">Ver ficha entregada</a></td></tr>
                        @empty<tr><td colspan="5" class="empty">No hay fichas entregadas pendientes de revisión.</td></tr>@endforelse
                    </tbody></table></div>
                </section>
                <div class="dashboard-grid">
                    <section class="panel" id="evaluaciones">
                        <h2>Resultados de evaluación</h2>
                        <p class="panel-description">Cumplimiento, nota y estado de las evaluaciones registradas.</p>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Caso</th>
                                        <th>Cumplimiento</th>
                                        <th>Nota</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($evaluaciones as $evaluacion)
                                        @php $porcentaje = (float)($evaluacion['porcentaje'] ?? 0); @endphp
                                        <tr>
                                            <td><strong>{{ $evaluacion['estudiante'] ?? '—' }}</strong><br><span
                                                    class="muted">{{ $evaluacion['curso'] ?? '' }}</span></td>
                                            <td>{{ $evaluacion['caso'] ?? '—' }}</td>
                                            <td><strong>{{ number_format($porcentaje, 0, ',', '.') }} %</strong></td>
                                            <td><strong>{{ isset($evaluacion['nota']) ? number_format((float) $evaluacion['nota'], 1, ',', '.') : '—' }}</strong>
                                            </td>
                                            <td><span
                                                    class="pill {{ $porcentaje >= 60 ? 'pass' : 'fail' }}">{{ $porcentaje >= 60 ? ($porcentaje >= 100 ? 'Excelente' : 'Aprobado') : 'Reprobado' }}</span>
                                            </td>
                                        </tr>
                                    @empty<tr>
                                            <td colspan="5" class="empty">No hay evaluaciones registradas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <div class="stack">
                        <section class="panel" id="accesos">
                            <h2>Accesos rápidos</h2>
                            <p class="panel-description">Herramientas para la gestión académica.</p>
                            <div class="quick-links"><a class="quick-link"
                                    href="{{ route('preview.docente.casos') }}"><span
                                        class="quick-link-icon">＋</span><span><strong>Casos
                                            clínicos</strong><small>Crear y administrar casos
                                            educativos</small></span></a><a class="quick-link" href="{{ route('preview.docente.asignaciones') }}"><span
                                        class="quick-link-icon">⇄</span><span><strong>Asignación de
                                            casos</strong><small>Organizar actividades por nivel y
                                            curso</small></span></a><a class="quick-link" href="{{ route('preview.docente.revision-fichas') }}"><span
                                        class="quick-link-icon">✓</span><span><strong>Revisión de
                                            fichas</strong><small>Consultar registros entregados</small></span></a><a
                                    class="quick-link" href="{{ route('preview.docente.evaluaciones') }}"><span
                                        class="quick-link-icon">%</span><span><strong>Evaluaciones</strong><small>Aplicar
                                            pautas y retroalimentación</small></span></a></div>
                        </section>
                        <section class="panel">
                            <h2>Indicadores de desempeño</h2>
                            <p class="panel-description">Resultados asociados a las actividades evaluadas.</p>
                            @forelse($indicadores as $indicador)
                                @php $valor = min(100,max(0,(float)($indicador['porcentaje'] ?? 0))); @endphp
                                <div class="meter">
                                    <div class="meter-top">
                                        <span>{{ $indicador['nombre'] ?? 'Indicador' }}</span><strong>{{ number_format($valor, 0, ',', '.') }}
                                            %</strong>
                                    </div>
                                    <div class="meter-track">
                                        <div class="meter-fill" style="width:{{ $valor }}%"></div>
                                    </div>
                            </div>@empty<p class="muted" style="font-size:12px">No hay indicadores disponibles.</p>
                            @endforelse
                        </section>
                    </div>
                </div>
                <section class="panel" id="casos" style="margin-top:16px">
                    <h2>Gestión de casos clínicos</h2>
                    <p class="panel-description">Administra casos de 3.º y 4.º medio desde el listado de casos clínicos.</p>
                    <div class="filter-bar">
                        <div class="form-field"><label for="nivelFiltro">Nivel educativo</label><select
                                id="nivelFiltro">
                                <option>Todos los niveles</option>
                                <option>3.º medio</option>
                                <option>4.º medio</option>
                            </select></div>
                        <div class="form-field"><label for="tipoFiltro">Tipo de actividad</label><select
                                id="tipoFiltro">
                                <option>Todos los tipos</option>
                                <option>Examen</option>
                                <option>Tarea</option>
                                <option>Material de apoyo</option>
                            </select></div>
                        <div class="form-field"><label for="cursoFiltro">Curso</label><select id="cursoFiltro">
                                <option>Todos los cursos</option>
                                <option>A</option>
                                <option>B</option>
                            </select></div><button class="btn btn-secondary" id="clearFilters" type="button">Limpiar
                            filtros</button>
                    </div>
                    <p class="muted" style="font-size:12px">La gestión y asignación detallada de casos se realizará
                        desde sus respectivas vistas.</p>
                </section>
            </main>
        </div>
    </div>
    <script>
        document.getElementById('clearFilters').addEventListener('click', () => {
            ['nivelFiltro', 'tipoFiltro', 'cursoFiltro'].forEach(id => document.getElementById(id).selectedIndex =
                0)
        });
    </script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
@include('shared.app-extras')
</body>

</html>
