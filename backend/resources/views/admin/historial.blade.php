<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Historial de actividad | Ficha Clínica TP</title>
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
        <a href="{{ route('preview.admin.dashboard') }}" class="nav-link">Dashboard</a>
        <div class="nav-title">Administración</div>
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
        <a href="{{ route('preview.admin.historial') }}" class="nav-link active" aria-current="page">Historial de actividad</a>
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
                <div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle"
                        aria-label="Mostrar u ocultar menú" aria-controls="sidebar"
                        aria-expanded="true"><span></span><span></span><span></span></button><strong>Historial de
                        actividad</strong></div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Administrador' }}</strong><small>Panel
                            administrativo</small></div>
                    <div class="user-avatar"><span>A</span></div>
                </div>
            </header>
            <main class="content">
                <div class="heading">
                    <div>
                        <h1>Historial de actividad</h1>
                        <p class="subtitle">Consulta y filtra los eventos registrados en la plataforma educativa.</p>
                    </div><a href="{{ route('preview.admin.dashboard') }}" class="primary-btn">Volver al dashboard</a>
                </div>
                <section class="panel" aria-labelledby="historyTitle">
                    <h2 id="historyTitle">Registro de actividades</h2>
                    <div class="history-toolbar">
                        <div class="history-field"><label for="historySearch">Buscar usuario o actividad</label><input
                                id="historySearch" type="search" placeholder="Nombre, correo, acción o módulo...">
                        </div>
                        <div class="history-field"><label for="historyRole">Rol</label><select id="historyRole">
                                <option value="">Todos los roles</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Docente">Docente</option>
                                <option value="Estudiante">Estudiante</option>
                            </select></div>
                        <div class="history-field"><label for="historyAction">Tipo de acción</label><select
                                id="historyAction">
                                <option value="">Todas las acciones</option>
                            </select></div>
                        <div class="history-field"><label for="historyModule">Módulo</label><select id="historyModule">
                                <option value="">Todos los módulos</option>
                            </select></div>
                    </div>
                    <div class="history-dates">
                        <div class="history-field"><label for="historyFrom">Desde</label><input type="date"
                                id="historyFrom"></div>
                        <div class="history-field"><label for="historyTo">Hasta</label><input type="date"
                                id="historyTo"></div><button type="button" class="history-btn admin-clean-button"
                            id="historyReset">Limpiar filtros</button>
                    </div>
                    <div class="history-summary"><span id="historyCount" aria-live="polite">0 actividades</span><span
                            id="historyDateError" role="status" style="color:#aa3b48"></span></div>
                    <div class="table-wrap">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Fecha y hora</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Acción</th>
                                    <th>Módulo</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody id="historyRows">
                                @forelse(($actividades ?? []) as $actividad)
                                    @php
                                        $fecha = data_get($actividad, 'created_at', data_get($actividad, 'fecha'));
                                        $fechaTexto = $fecha
                                            ? \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y H:i')
                                            : '—';
                                        $fechaISO = $fecha
                                            ? \Illuminate\Support\Carbon::parse($fecha)->format('Y-m-d')
                                            : '';
                                        $nombre = (string) data_get(
                                            $actividad,
                                            'usuario',
                                            data_get($actividad, 'user.name', 'Usuario no identificado'),
                                        );
                                        $correo = (string) data_get(
                                            $actividad,
                                            'correo',
                                            data_get($actividad, 'user.email', ''),
                                        );
                                        $rol = (string) data_get($actividad, 'rol', '—');
                                        $accion = (string) data_get($actividad, 'accion', '—');
                                        $modulo = (string) data_get($actividad, 'modulo', '—');
                                        $detalle = (string) data_get(
                                            $actividad,
                                            'detalle',
                                            'Sin información adicional.',
                                        );
                                    @endphp
                                    <tr class="history-record" data-date="{{ $fechaISO }}"
                                        data-role="{{ $rol }}" data-action="{{ $accion }}"
                                        data-module="{{ $modulo }}" data-user="{{ $nombre }}"
                                        data-email="{{ $correo }}" data-datetime="{{ $fechaTexto }}"
                                        data-detail="{{ $detalle }}">
                                        <td>{{ $fechaTexto }}</td>
                                        <td><span class="history-name">{{ $nombre }}</span>
                                            @if ($correo)
                                                <span class="history-sub">{{ $correo }}</span>
                                            @endif
                                        </td>
                                        <td><span
                                                class="history-pill {{ $rol === 'Docente' ? 'teacher' : ($rol === 'Estudiante' ? 'student' : 'admin') }}">{{ $rol }}</span>
                                        </td>
                                        <td>{{ $accion }}</td>
                                        <td>{{ $modulo }}</td>
                                        <td><button type="button" class="history-details">Ver detalles</button></td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr id="historyEmpty">
                                    <td colspan="6" class="history-empty">No hay actividades registradas para
                                        mostrar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="history-footer"><span id="historyRange">Mostrando 0 registros</span>
                        <div class="history-pages"><button id="historyPrev" type="button">Anterior</button><span
                                id="historyPage">Página 1 de 1</span><button id="historyNext"
                                type="button">Siguiente</button></div>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <div class="history-modal" id="historyModal" hidden role="dialog" aria-modal="true"
        aria-labelledby="historyModalTitle">
        <div class="history-dialog">
            <div class="history-dialog-head">
                <h2 id="historyModalTitle">Detalle de actividad</h2><button type="button" class="history-close"
                    id="historyCloseX" aria-label="Cerrar">×</button>
            </div>
            <div class="history-dialog-body">
                <dl class="history-detail-grid">
                    <dt>Fecha y hora</dt>
                    <dd id="detailDate"></dd>
                    <dt>Usuario</dt>
                    <dd id="detailUser"></dd>
                    <dt>Correo</dt>
                    <dd id="detailEmail"></dd>
                    <dt>Rol</dt>
                    <dd id="detailRole"></dd>
                    <dt>Acción</dt>
                    <dd id="detailAction"></dd>
                    <dt>Módulo</dt>
                    <dd id="detailModule"></dd>
                    <dt>Descripción</dt>
                    <dd id="detailDescription"></dd>
                </dl>
            </div>
            <div class="history-dialog-foot"><button type="button" class="history-btn"
                    id="historyClose">Cerrar</button></div>
        </div>
    </div>
    <script>
        (() => {
            'use strict';
            const $ = id => document.getElementById(id),
                records = [...document.querySelectorAll('.history-record')],
                pageSize = 10;
            let page = 1,
                filtered = [],
                lastFocus = null;
            const norm = v => String(v || '').toLocaleLowerCase('es');

            function addOptions(id, key) {
                const values = [...new Set(records.map(r => r.dataset[key]).filter(Boolean))].sort((a, b) => a
                    .localeCompare(b, 'es'));
                for (const value of values) {
                    const o = document.createElement('option');
                    o.value = value;
                    o.textContent = value;
                    $(id).append(o);
                }
            }
            addOptions('historyAction', 'action');
            addOptions('historyModule', 'module');

            function render() {
                const q = norm($('historySearch').value.trim()),
                    role = $('historyRole').value,
                    action = $('historyAction').value,
                    module = $('historyModule').value,
                    from = $('historyFrom').value,
                    to = $('historyTo').value;
                const invalid = Boolean(from && to && from > to);
                $('historyDateError').textContent = invalid ? 'La fecha inicial no puede ser posterior a la final.' :
                    '';
                filtered = invalid ? [] : records.filter(r => (!q || norm([r.dataset.user, r.dataset.email, r.dataset
                    .action, r.dataset.module, r.dataset.detail
                ].join(' ')).includes(q)) && (!role || r.dataset.role === role) && (!action || r.dataset
                    .action === action) && (!module || r.dataset.module === module) && (!from || r.dataset
                    .date >= from) && (!to || r.dataset.date <= to));
                const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
                page = Math.min(Math.max(1, page), pages);
                records.forEach(r => r.hidden = true);
                filtered.slice((page - 1) * pageSize, page * pageSize).forEach(r => r.hidden = false);
                $('historyEmpty').hidden = filtered.length > 0;
                $('historyEmpty').querySelector('td').textContent = records.length === 0 ?
                    'No hay actividades registradas para mostrar.' :
                    'No se encontraron actividades con los filtros seleccionados.';
                $('historyCount').textContent = filtered.length + ' actividad' + (filtered.length === 1 ? '' : 'es');
                $('historyRange').textContent = filtered.length ? 'Mostrando ' + ((page - 1) * pageSize + 1) + '–' +
                    Math.min(page * pageSize, filtered.length) + ' de ' + filtered.length + ' registros' :
                    'Mostrando 0 registros';
                $('historyPage').textContent = 'Página ' + page + ' de ' + pages;
                $('historyPrev').disabled = page === 1;
                $('historyNext').disabled = page === pages;
            }
            ['historySearch', 'historyRole', 'historyAction', 'historyModule', 'historyFrom', 'historyTo'].forEach(id =>
                $(id).addEventListener(id === 'historySearch' ? 'input' : 'change', () => {
                    page = 1;
                    render();
                }));
            $('historyReset').addEventListener('click', () => {
                for (const id of ['historySearch', 'historyRole', 'historyAction', 'historyModule',
                        'historyFrom', 'historyTo'
                    ]) $(id).value = '';
                page = 1;
                render();
            });
            $('historyPrev').addEventListener('click', () => {
                if (page > 1) {
                    page--;
                    render();
                }
            });
            $('historyNext').addEventListener('click', () => {
                if (page * pageSize < filtered.length) {
                    page++;
                    render();
                }
            });

            function close() {
                if ($('historyModal').hidden) return;
                $('historyModal').hidden = true;
                document.body.style.overflow = '';
                lastFocus?.focus();
            }
            $('historyRows').addEventListener('click', e => {
                const btn = e.target.closest('.history-details');
                if (!btn) return;
                const d = btn.closest('tr').dataset;
                for (const [field, value] of Object.entries({
                        detailDate: d.datetime,
                        detailUser: d.user,
                        detailEmail: d.email || '—',
                        detailRole: d.role,
                        detailAction: d.action,
                        detailModule: d.module,
                        detailDescription: d.detail || 'Sin información adicional.'
                    })) $(field).textContent = value;
                lastFocus = btn;
                $('historyModal').hidden = false;
                document.body.style.overflow = 'hidden';
                $('historyClose').focus();
            });
            $('historyClose').addEventListener('click', close);
            $('historyCloseX').addEventListener('click', close);
            $('historyModal').addEventListener('click', e => {
                if (e.target === $('historyModal')) close();
            });
            document.addEventListener('keydown', e => {
                if ($('historyModal').hidden) return;
                if (e.key === 'Escape') close();
                if (e.key === 'Tab') {
                    const first = $('historyCloseX'),
                        last = $('historyClose');
                    if (e.shiftKey && document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    } else if (!e.shiftKey && document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            });
            const toggle = $('menuToggle'),
                sidebar = $('sidebar');
            toggle?.addEventListener('click', () => {
                const hidden = document.querySelector('.app')?.classList.toggle('sidebar-hidden');
                toggle.setAttribute('aria-expanded', String(!hidden));
                toggle.setAttribute('aria-label', hidden ? 'Mostrar menú lateral' : 'Ocultar menú lateral');
            });
            render();
        })();
    </script>
@include('shared.app-extras')
</body>

</html>
