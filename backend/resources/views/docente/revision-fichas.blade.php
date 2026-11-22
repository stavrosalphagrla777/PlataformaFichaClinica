<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisión de fichas clínicas | Docente</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ajustes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ficha-legible.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-registro-legible.css') }}">
<style>#reviewDownloadPDF[hidden]{display:none!important}</style>
</head>

<body>
    <div class="app" id="app">
        @include('shared.navigation')
        <div class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle" type="button" aria-label="Mostrar u ocultar menú"
                        aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button>
                    <strong>Revisión de fichas clínicas</strong>
                </div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content">
                <div class="heading">
                    <div>
                        <h1>Revisión de fichas clínicas</h1>
                        <p class="subtitle">Consulta la última versión guardada de las fichas de tus estudiantes.</p>
                    </div>
                </div>
                <section class="stats" aria-label="Resumen de fichas">
                    <div class="stat"><span class="stat-label">Fichas con progreso guardado</span><strong
                            class="stat-value" id="metricTotal">0</strong></div>
                    <div class="stat"><span class="stat-label">En desarrollo</span><strong class="stat-value"
                            id="metricProgress">0</strong></div>
                    <div class="stat"><span class="stat-label">Entregadas</span><strong class="stat-value"
                            id="metricDelivered">0</strong></div>
                    <div class="stat"><span class="stat-label">Cerradas por tiempo</span><strong class="stat-value"
                            id="metricExpired">0</strong>
                    </div>
                </section>
                <section class="panel" aria-label="Filtros de fichas">
                    <div class="review-filters">
                        <div class="form-field"><label for="reviewJornada">Jornada de estudiantes</label><select id="reviewJornada"><option value="diurno">Diurnos</option><option value="vespertino">Vespertinos (V3ro / V4to)</option></select></div>
                        <div class="form-field"><label for="reviewSearch">Buscar estudiante o caso</label><input
                                id="reviewSearch" type="search" placeholder="Nombre, código o caso..."></div>
                        <div class="form-field"><label for="reviewLevel">Nivel</label><select id="reviewLevel">
                                <option value="">Todos los niveles</option>
                                <option value="3.º medio">3.º medio</option>
                                <option value="4.º medio">4.º medio</option>
                            </select></div>
                        <div class="form-field"><label for="reviewCourse">Curso</label><select id="reviewCourse">
                                <option value="">Todos los cursos</option>
                            </select></div>
                        <div class="form-field"><label for="reviewStatus">Estado</label><select id="reviewStatus">
                                <option value="">Todos los estados</option>
                                <option value="En desarrollo">En desarrollo</option>
                                <option value="Entregada">Entregada</option>
                                <option value="Tiempo agotado">Tiempo agotado</option>
                            </select></div>
                        <div class="review-clear-field"><button type="button" class="btn btn-secondary" id="reviewClear">Limpiar filtros</button></div>
                    </div>
                </section>
                <section class="panel" aria-label="Listado de fichas">
                    <div class="table-wrap">
                        <table class="teacher-table">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Caso clínico</th>
                                    <th>Curso</th>
                                    <th>Estado</th>
                                    <th>Último guardado</th>
                                    <th>Progreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="reviewRows"></tbody>
                        </table>
                    </div>
                    <p class="empty" id="reviewEmpty" hidden>No se encontraron fichas guardadas para estos
                        filtros.</p>
                </section>
                <section class="panel review-details" id="reviewDetails" hidden aria-labelledby="reviewDetailTitle">
                    <div class="review-detail-head">
                        <div>
                            <h2 id="reviewDetailTitle">Detalle de ficha</h2>
                            <p id="reviewDetailSubtitle"></p>
                        </div><button class="btn btn-secondary" id="closeReview" type="button">Cerrar
                            revisión</button>
                    </div>
                    <div class="review-detail-grid">
                        <div class="review-detail-info"><small>Estado</small><strong id="detailStatus">—</strong>
                        </div>
                        <div class="review-detail-info"><small>Último guardado</small><strong
                                id="detailSaved">—</strong></div>
                        <div class="review-detail-info"><small>Secciones guardadas</small><strong
                                id="detailSections">—</strong></div>
                        <div class="review-detail-info"><small>Duración del caso</small><strong
                                id="detailDuration">—</strong></div>
                    </div>
                    <div class="review-section-list" id="reviewSections"></div>
                    <div class="review-detail-footer">
                        <p class="review-notice" id="reviewNotice"></p><a class="btn btn-secondary" id="reviewDownloadPDF" href="#" hidden>Descargar pauta PDF</a> <button class="btn btn-primary"
                            type="button" id="reviewEvaluate" disabled>Ir a evaluación</button>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/docente/registro-legible.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-api.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-ui.js') }}" defer></script>
    <script src="{{ asset('js/docente/revision-fichas.js') }}" defer></script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
@include('shared.app-extras')
</body>

</html>
