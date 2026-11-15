<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Casos clínicos | Ficha Clínica TP</title>
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
                        aria-label="Mostrar u ocultar menú" aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button><strong>Casos clínicos</strong></div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content">
                <div class="heading">
                    <div>
                        <h1>Casos clínicos</h1>
                        <p class="subtitle">Administra los casos educativos para 3.º y 4.º medio.</p>
                    </div><a class="btn btn-primary" href="{{ route('preview.docente.crear-caso') }}">＋ Crear caso
                        clínico</a>
                </div>
                @php
                    $casos = $casos ?? [];
                @endphp
                <section class="panel">
                    <h2>Listado de casos clínicos</h2>
                    <p class="panel-description">Consulta, filtra y administra los casos de simulación.</p>
                    <div class="teacher-filters">
                        <div class="form-field"><label for="buscar">Buscar caso</label><input id="buscar"
                                placeholder="Código, título o descripción"></div>
                        <div class="form-field"><label for="nivel">Nivel educativo</label><select id="nivel">
                                <option value="">Todos</option>
                                <option>3.º medio</option>
                                <option>4.º medio</option>
                                <option>Ambos niveles</option>
                            </select></div>
                        <div class="form-field"><label for="tipo">Tipo de actividad</label><select id="tipo">
                                <option value="">Todos</option>
                                <option>Examen</option>
                                <option>Tarea</option>
                                <option>Material de apoyo</option>
                            </select></div>
                        <div class="form-field"><label for="estado">Estado</label><select id="estado">
                                <option value="">Todos</option>
                                <option>Borrador</option>
                                <option>Publicado</option>
                                <option>Archivado</option>
                            </select></div>
                        <div class="form-field"><label for="propios">Casos creados por mí</label><select id="propios">
                                <option value="">Todos</option>
                                <option value="si">Sí</option>
                                <option value="no">No</option>
                            </select></div>
                        <button type="button" class="btn btn-secondary" id="limpiar">Limpiar filtros</button>
                    </div>
                    <div class="table-wrap">
                        <table class="teacher-table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Caso clínico</th>
                                    <th>Nivel</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Duración</th>
                                    <th>Autoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="caseRows">
                                @forelse($casos as $caso)
                                    <tr data-search="{{ mb_strtolower(($caso['codigo'] ?? '') . ' ' . ($caso['titulo'] ?? '') . ' ' . html_entity_decode(strip_tags(($caso['descripcion'] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}"
                                        data-nivel="{{ $caso['nivel'] ?? '' }}" data-tipo="{{ $caso['tipo'] ?? '' }}"
                                        data-estado="{{ $caso['estado'] ?? '' }}" data-propio="{{ !empty($caso['creadoPorMi']) ? 'si' : 'no' }}">
                                        <td><strong>{{ $caso['codigo'] ?? '—' }}</strong></td>
                                        <td><strong>{{ $caso['titulo'] ?? '—' }}</strong><span
                                                class="teacher-subtext">{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags(preg_replace('/<\/(p|h[1-6]|li)>/i', ' ', ($caso['descripcion'] ?? ''))), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 130) }}</span></td>
                                        <td>{{ $caso['nivel'] ?? '—' }}</td>
                                        <td><span class="pill">{{ $caso['tipo'] ?? '—' }}</span></td>
                                        <td><span class="pill">{{ $caso['estado'] ?? '—' }}</span></td>
                                        <td>{{ isset($caso['duracion']) && $caso['duracion'] !== '' ? $caso['duracion'] . ' min' : '—' }}
                                        </td>
                                        <td>@if(!empty($caso['creadoPorMi']))<span class="case-own-badge">Creado por mí</span>@else<span class="case-other-author" title="{{ $caso['creadoPor'] ?? 'Otro docente' }}">—</span>@endif</td>
                                        <td><button type="button" class="btn btn-secondary btn-small"
                                                data-view='@json($caso)'>Ver detalles</button></td>
                                </tr>@empty<tr id="emptyData">
                                        <td colspan="8" class="empty">No hay casos clínicos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="teacher-count" id="resultCount"></p>
                    <p class="teacher-count" id="caseActionMessage" role="status" aria-live="polite"></p>
                </section>
                <div class="modal-overlay" id="detailModal" hidden>
                    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                        <div class="modal-header">
                            <h2 id="modalTitle">Detalles del caso</h2>
                        </div>
                        <div class="modal-body">
                            <dl class="teacher-details" id="detailContent"></dl>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="deleteCase" hidden>Eliminar caso</button>
                            <button type="button" class="btn btn-secondary" id="closeModal">Cerrar</button>
                        </div>
                        <p class="case-delete-error" id="caseDeleteError" role="alert" hidden></p>
                    </div>
                </div>
                <div class="modal-overlay" id="deleteConfirmModal" hidden>
                    <div class="modal-card confirm-modal case-delete-confirm" role="alertdialog" aria-modal="true"
                        aria-labelledby="deleteConfirmTitle" aria-describedby="deleteConfirmText" tabindex="-1">
                        <div class="confirm-modal-icon" aria-hidden="true">!</div>
                        <h2 id="deleteConfirmTitle">Eliminar caso clínico</h2>
                        <p id="deleteConfirmText">¿Estás seguro de que deseas eliminar este caso?</p>
                        <p class="case-delete-warning">Esta acción no se puede deshacer. Sus asignaciones sin fichas iniciadas también se eliminarán.
                            Si hay estudiantes que iniciaron el caso, el sistema impedirá la eliminación.</p>
                        <div class="confirm-modal-actions">
                            <button type="button" class="btn btn-secondary" id="cancelDeleteCase">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteCase">Sí, eliminar caso</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/docente/docente-api.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-ui.js') }}" defer></script>
    <script src="{{ asset('js/docente/casos.js') }}" defer></script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
</body>

</html>
