<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Procedimientos | Ficha Clínica TP</title>

    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">

    <style>
        .anamnesis-container {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
            align-items: stretch;
        }

        .anamnesis-card {
            grid-column: span 2;
            min-width: 0;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 9px;
            padding: 12px;
            box-shadow: 0 2px 9px rgba(15, 42, 76, .045);
        }

        .anamnesis-card.wide {
            grid-column: span 3;
        }

        .anamnesis-header {
            padding: 9px 12px;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        .anamnesis-header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 750;
            color: #12416e;
        }

        .anamnesis-header.rosa {
            background: #fce6eb;
        }

        .anamnesis-header.azul {
            background: #e0edff;
        }

        .anamnesis-header.naranja {
            background: #fff0d8;
        }

        .anamnesis-header.lila {
            background: #eee5ff;
        }

        .anamnesis-header.verde {
            background: #ddf7eb;
        }

        .anamnesis-header.celeste {
            background: #deebff;
        }

        .anamnesis-header.rosado {
            background: #fce1e9;
        }

        .anamnesis-header.menta {
            background: #d9f5eb;
        }

        .anamnesis-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
            margin-bottom: 10px;
        }

        .anamnesis-field label,
        .anamnesis-label {
            font-size: 12px;
            font-weight: 650;
            color: #25354b;
        }

        .anamnesis-field input:not([type=radio]),
        .anamnesis-field select,
        .anamnesis-field textarea {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            color: #172a41;
            padding: 9px 10px;
            font: 13px 'Segoe UI', Arial, sans-serif;
        }

        .anamnesis-field textarea {
            min-height: 95px;
            resize: vertical;
        }

        .anamnesis-card.wide textarea {
            min-height: 130px;
        }

        .anamnesis-field input:focus,
        .anamnesis-field select:focus,
        .anamnesis-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px;
        }

        .anamnesis-options {
            display: grid;
            gap: 7px;
            margin: 9px 0 12px;
        }

        .anamnesis-options label {
            display: flex;
            gap: 7px;
            align-items: center;
            font-size: 12px;
            color: #34465d;
            cursor: pointer;
        }

        .anamnesis-options input {
            accent-color: #0968bd;
        }

        .anamnesis-habits {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .anamnesis-habits .anamnesis-field {
            margin-bottom: 0;
        }

        .anamnesis-hidden {
            display: none !important;
        }

        .anamnesis-footer {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .anamnesis-button {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 11px 18px;
            background: #eef3f8;
            color: #243950;
            font-weight: 650;
            cursor: pointer;
        }

        .anamnesis-button.primary {
            background: #0968bd;
            border-color: #0968bd;
            color: white;
        }

        .anamnesis-button:hover {
            filter: brightness(.96);
        }

        .anamnesis-message {
            display: none;
            flex: 1 1 100%;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
        }

        .anamnesis-message.show {
            display: block;
        }

        .anamnesis-message.ok {
            background: #e8f7ee;
            color: #14633a;
        }

        .anamnesis-message.error {
            background: #fff0ed;
            color: #a32b25;
        }

        @media(max-width:1050px) {
            .anamnesis-container {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .anamnesis-card,
            .anamnesis-card.wide {
                grid-column: span 1;
            }
        }

        @media(max-width:650px) {
            .anamnesis-container {
                grid-template-columns: 1fr;
            }

            .anamnesis-card,
            .anamnesis-card.wide {
                grid-column: 1;
            }

            .anamnesis-habits {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Distribución de examen físico general y segmentario */
        .ef-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            align-items: start;
            margin-top: 18px
        }

        .ef-panel {
            min-width: 0;
            padding: 18px;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(15, 42, 76, .045)
        }

        .ef-title {
            margin: 0 0 18px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #e6f0fc;
            color: #174c7e;
            font-size: 18px
        }

        .ef-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .ef-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 0
        }

        .ef-field:last-child {
            grid-column: 1/-1
        }

        .ef-field label,
        .ef-segment-body label {
            font-size: 13px;
            font-weight: 650;
            color: #25354b
        }

        .ef-field select,
        .ef-field input[type="number"],
        .ef-field textarea,
        .ef-segment-body textarea {
            width: 100%;
            min-width: 0;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            font: inherit;
            font-size: 13px
        }

        .ef-field textarea,
        .ef-segment-body textarea {
            min-height: 90px;
            resize: vertical
        }

        .ef-field select:focus,
        .ef-field input[type="number"]:focus,
        .ef-field textarea:focus,
        .ef-segment-body textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .ef-hint {
            margin: -6px 0 15px;
            font-size: 12px;
            color: #64748b
        }

        .ef-segment {
            margin: 0 0 10px;
            border: 1px solid #dce5ef;
            border-radius: 9px;
            overflow: hidden;
            background: #fff
        }

        .ef-segment summary {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 14px 38px 14px 15px;
            cursor: pointer;
            list-style: none;
            color: #174c7e
        }

        .ef-segment summary::-webkit-details-marker {
            display: none
        }

        .ef-segment summary:after {
            content: '+';
            position: absolute;
            right: 15px;
            top: 14px;
            font-size: 20px;
            color: #1766aa
        }

        .ef-segment[open] summary:after {
            content: '−'
        }

        .ef-segment summary span {
            font-weight: 700;
            font-size: 15px
        }

        .ef-segment summary small {
            color: #64748b;
            font-size: 11px
        }

        .ef-segment-body {
            display: flex;
            flex-direction: column;
            gap: 9px;
            padding: 14px;
            border-top: 1px solid #e7edf3;
            background: #fbfdff
        }

        .ef-segment-body textarea+label {
            margin-top: 7px
        }

        .ef-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px
        }

        @media(max-width:1050px) {
            .ef-columns {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:550px) {
            .ef-panel {
                padding: 12px
            }

            .ef-fields {
                grid-template-columns: 1fr
            }

            .ef-actions button {
                flex: 1
            }
        }

        /* Procedimientos: mantiene el lenguaje visual de las vistas anteriores */
        .proc-shortcuts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px
        }

        .proc-shortcut {
            border: 1px solid #cbdff5;
            border-radius: 7px;
            background: #f4f8ff;
            color: #174c7e;
            padding: 8px 12px;
            font: 600 12px 'Segoe UI', Arial, sans-serif;
            cursor: pointer;
            transition: background .15s, border-color .15s
        }

        .proc-shortcut:hover {
            background: #e4f0ff;
            border-color: #93c5fd
        }

        .proc-shortcut[aria-pressed="true"] {
            background: #1766aa;
            color: #fff;
            border-color: #1766aa
        }

        .proc-shortcut:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px
        }

        .proc-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .proc-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .proc-full {
            grid-column: 1/-1
        }

        .proc-fields .ef-field:last-child {
            grid-column: 1/-1
        }

        .proc-fields .ef-field input[type="text"],
        .proc-fields .ef-field input[type="date"],
        .proc-fields .ef-field input[type="time"] {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            min-height: 42px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            font: inherit;
            font-size: 13px
        }

        .proc-fields .ef-field input:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .proc-fields .ef-field[hidden] {
            display: none !important
        }

        .proc-actions {
            margin-top: 18px
        }

        .proc-title-secondary {
            background: #e6f6ef;
            color: #166347
        }

        /* Historial en formato tabla, acorde al estándar visual de las otras vistas */
        .proc-list {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #dce5ef;
            border-radius: 8px
        }

        .proc-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
            color: #47627e
        }

        .proc-table thead {
            background: #0d4b83;
            color: #fff
        }

        .proc-table th {
            padding: 13px 14px;
            font-weight: 700;
            white-space: nowrap
        }

        .proc-table td {
            padding: 14px;
            border-bottom: 1px solid #dce5ef;
            vertical-align: middle
        }

        .proc-table tbody tr:nth-child(odd) {
            background: #f6f9fc
        }

        .proc-table tbody tr:nth-child(even) {
            background: #fff
        }

        .proc-table tbody tr:last-child td {
            border-bottom: 0
        }

        .proc-table .proc-details-row[hidden] {
            display: none
        }

        .proc-table .proc-details-row td {
            padding: 13px 14px;
            background: #edf5ff;
            color: #334155;
            line-height: 1.6
        }

        .proc-table .proc-details-row p {
            margin: 4px 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere
        }

        .proc-view {
            border: 0;
            background: none;
            color: #1766d1;
            font: inherit;
            cursor: pointer;
            padding: 4px 0
        }

        .proc-view:hover {
            text-decoration: underline
        }

        .proc-delete {
            background: #fff;
            border: 1px solid #efc4c4;
            color: #a32b25;
            border-radius: 7px;
            padding: 7px 10px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 10px
        }

        .proc-empty {
            padding: 22px;
            text-align: center;
            color: #64748b;
            font-size: 13px
        }

        @media(max-width:1050px) {
            .proc-layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {
            .proc-fields {
                grid-template-columns: 1fr
            }

            .proc-actions {
                justify-content: flex-start
            }
        }
    </style>
    <!-- Contexto autorizado de 4.º medio; nunca contiene datos de otros estudiantes. -->
    <script>window.__FICHA_4TO = @json($ficha4toContext ?? null);</script>
    <link rel="stylesheet" href="{{ asset('css/estudiante-confirmacion-4to.css') }}">
    <script src="{{ asset('js/estudiante/4to-backend-bridge.js') }}"></script>
</head>

<body>
    <div class="app app-shell" id="appShell">

        <!-- Menú lateral -->
        @include('estudiante.partials.sidebar-4to')

        <div class="main-area">

            <!-- Barra superior -->
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú"
                        aria-controls="sidebar" aria-expanded="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="topbar-title">Procedimientos</span>
                </div>

                <div class="user-info">
                    <div class="user-details">
                        <strong>Estudiante</strong>
                        <small>4.º medio</small>
                    </div>
                    <div class="user-avatar">E</div>
                </div>
            </header>

            <main class="content">

                <!-- Encabezado -->
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA</p>
                        <h1>Procedimientos del paciente</h1>
                        <p class="muted">
                            Registro de procedimientos realizados durante la atención del caso clínico simulado.
                        </p>
                    </div>

                    <a class="btn btn-secondary" id="backToCase" href="{{ route('preview.estudiante.casos') }}">
                        ← Volver al caso
                    </a>
                </div>

                <!-- Caso clínico -->
                <div class="case-banner">
                    <div>
                        <span class="case-code" id="caseCode">CAS-001</span>
                        <strong id="caseTitle">Caso clínico</strong>
                        <small>Paciente simulado · Procedimientos</small>
                    </div>

                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- Navegación entre etapas -->
                <nav class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                    <a id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                    <a id="tabSignos" href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a>
                    <a id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}">ABCDE</a>
                    <a id="tabAnamnesis" href="{{ route('preview.estudiante.anamnesis') }}">Anamnesis</a>
                    <a id="tabExaFisico" href="{{ route('preview.estudiante.examen-fisico') }}">Examen físico</a>
                    <a class="selected" id="tabProcedimientos" href="{{ route('preview.estudiante.procedimientos') }}"
                        aria-current="page">Procedimientos</a>
                    <a id="tabMedicamentos" href="{{ route('preview.estudiante.medicamentos_4to') }}">Medicamentos</a>
                    <a id="tabExamenes" href="#">Exámenes</a>
                    <a id="tabAccEnfermeria" href="#">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </nav>

                <!-- Procedimientos de 4.º medio. Los datos se almacenan por caso y sesión. -->
                <form id="procedimientosForm" novalidate>
                    <div class="proc-layout">
                        <section class="ef-panel" aria-labelledby="registroTitle">
                            <h2 class="ef-title" id="registroTitle">Registro de procedimiento</h2>
                            <p class="ef-hint">Completa los datos del procedimiento realizado en el caso simulado. Los
                                campos con * son obligatorios.</p>
                            <div class="proc-fields">
                                <div class="ef-field proc-full">
                                    <label for="tipo">Tipo de procedimiento *</label>
                                    <select id="tipo" name="tipo" required>
                                        <option value="">Seleccionar procedimiento</option>
                                        <option value="ECG">ECG - Electrocardiograma</option>
                                        <option value="Vía venosa">Vía venosa</option>
                                        <option value="Sonda Foley">Sonda Foley</option>
                                        <option value="Signos vitales">Signos vitales</option>
                                        <option value="Soporte vital">Soporte vital</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    <div class="proc-shortcuts" role="group"
                                        aria-label="Seleccionar procedimiento rápidamente">
                                        <button type="button" class="proc-shortcut" data-procedimiento="ECG"
                                            aria-pressed="false">ECG</button>
                                        <button type="button" class="proc-shortcut" data-procedimiento="Vía venosa"
                                            aria-pressed="false">Vía venosa</button>
                                        <button type="button" class="proc-shortcut" data-procedimiento="Sonda Foley"
                                            aria-pressed="false">Sonda Foley</button>
                                        <button type="button" class="proc-shortcut"
                                            data-procedimiento="Signos vitales" aria-pressed="false">Signos
                                            vitales</button>
                                        <button type="button" class="proc-shortcut"
                                            data-procedimiento="Soporte vital" aria-pressed="false">Soporte
                                            vital</button>
                                        <button type="button" class="proc-shortcut" data-procedimiento="Otro"
                                            aria-pressed="false">Otro</button>
                                    </div>
                                </div>
                                <div class="ef-field proc-full" id="otroWrap" hidden>
                                    <label for="otro">Especificar procedimiento *</label>
                                    <input type="text" id="otro" name="otro" maxlength="120"
                                        placeholder="Escribe el procedimiento realizado">
                                </div>
                                <div class="ef-field"><label for="fecha">Fecha *</label><input type="date"
                                        id="fecha" name="fecha" required></div>
                                <div class="ef-field"><label for="hora">Hora *</label><input type="time"
                                        id="hora" name="hora" required></div>
                                <!-- BACKEND (Laravel): el responsable debe obtenerse del usuario autenticado, no del caso clínico.
                                     Usar Auth::user()->name (o el atributo de nombre real del modelo User).
                                     Al persistir en MySQL, el controlador debe asignar responsable_id = Auth::id()
                                     y obtener el nombre desde el usuario autenticado; no confiar en el valor enviado por el navegador.
                                     En la vista previa sin sesión autenticada, el campo queda editable para probar el formulario. -->
                                <div class="ef-field proc-full"><label for="responsable">Responsable (nombre del
                                        estudiante) *</label><input type="text" id="responsable"
                                        name="responsable" maxlength="120" required
                                        value="{{ auth()->user()?->name ?? '' }}" @auth readonly @endauth
                                        placeholder="{{ auth()->check() ? '' : 'Vista previa: ingresa un nombre para probar' }}">
                                </div>
                                <div class="ef-field proc-full"><label for="descripcion">Descripción del procedimiento
                                        *</label>
                                    <textarea id="descripcion" name="descripcion" maxlength="2000" required
                                        placeholder="Describe brevemente cómo se realizó el procedimiento..."></textarea>
                                </div>
                            </div>
                            <div id="saveMessage" class="anamnesis-message" role="status" aria-live="polite"></div>
                            <div class="ef-actions proc-actions">
                                <button type="button" class="anamnesis-button" id="clearButton">Limpiar</button>
                                <button type="button" class="anamnesis-button" id="cancelButton">Cancelar</button>
                                <button type="submit" class="anamnesis-button primary">Guardar procedimiento</button>
                            </div>
                        </section>
                        <section class="ef-panel" aria-labelledby="listadoTitle">
                            <h2 class="ef-title proc-title-secondary" id="listadoTitle">Procedimientos registrados
                            </h2>
                            <div id="procedimientosLista" class="proc-list" aria-live="polite"></div>
                        </section>
                    </div>
                </form>
            </main>
        </div>
    </div>


    <!-- Confirmación de limpieza con el mismo estilo de Triage y ABCDE -->
    <div class="modal-overlay" id="clearModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="clearModalTitle"
            aria-describedby="clearModalDescription">
            <div class="modal-icon">!</div>
            <h2 id="clearModalTitle">Limpiar formulario</h2>
            <p id="clearModalDescription">¿Estás seguro de que deseas limpiar los campos del procedimiento actual? Los
                procedimientos ya guardados se conservarán.</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="cancelClear">Cancelar</button>
                <button type="button" class="modal-confirm" id="confirmClear">Sí, limpiar</button>
            </div>
        </div>
    </div>

    <!-- Confirmación de eliminación: mismo diseño del modal Limpiar -->
    <div class="modal-overlay" id="deleteModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle"
            aria-describedby="deleteModalDescription">
            <div class="modal-icon">!</div>
            <h2 id="deleteModalTitle">Eliminar procedimiento</h2>
            <p id="deleteModalDescription">¿Estás seguro de que deseas eliminar este procedimiento registrado? Esta
                acción no se puede deshacer.</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="cancelDelete">Cancelar</button>
                <button type="button" class="modal-confirm" id="confirmDelete">Sí, eliminar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';
            const $ = id => document.getElementById(id);
            const form = $('procedimientosForm');
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };
            const solicitado = new URLSearchParams(location.search).get('caso');
            const casoId = window.__FICHA_4TO?.codigo || (Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001');
            const casoQuery = '?caso=' + encodeURIComponent(casoId);
            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = window.__FICHA_4TO?.titulo || casos[casoId] || 'Caso clínico';
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}/" + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + casoQuery,
                triage: "{{ route('preview.estudiante.triage') }}" + casoQuery,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + casoQuery,
                abcde: "{{ route('preview.estudiante.abcde') }}" + casoQuery,
                anamnesis: "{{ route('preview.estudiante.anamnesis') }}" + casoQuery,
                examen: "{{ route('preview.estudiante.examen-fisico') }}" + casoQuery,
                procedimientos: "{{ route('preview.estudiante.procedimientos') }}" + casoQuery
            };
            const enlaces = {
                sidebarFicha: rutas.detalle,
                sidebarAdmision: rutas.admision,
                sidebarTriage: rutas.triage,
                sidebarSignos: rutas.signos,
                sidebarABCDE: rutas.abcde,
                sidebarAnamnesis: rutas.anamnesis,
                tabResumen: rutas.detalle,
                tabAdmision: rutas.admision,
                tabTriage: rutas.triage,
                tabSignos: rutas.signos,
                tabABCDE: rutas.abcde,
                tabAnamnesis: rutas.anamnesis,
                tabExaFisico: rutas.examen,
                tabProcedimientos: rutas.procedimientos,
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle').addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });
            // BACKEND: nombre proporcionado por la sesión autenticada de Laravel.
            // No guardar la identidad del responsable como un dato libre enviado por el cliente:
            // el controlador debe asociar cada procedimiento al usuario autenticado (Auth::id()).
            const nombreEstudianteSesion = @json(auth()->user()?->name ?? '');

            function restaurarResponsable() {
                if (nombreEstudianteSesion) $('responsable').value = nombreEstudianteSesion;
            }
            restaurarResponsable();
            const key = 'fctp_procedimientos_v1_' + casoId;
            const mensaje = $('saveMessage');
            let registros = [];
            try {
                const guardados = JSON.parse(sessionStorage.getItem(key) || '[]');
                if (Array.isArray(guardados)) registros = guardados;
            } catch (error) {
                console.warn('No se pudieron recuperar los procedimientos:', error);
            }

            let avisoTimer = null;
            function ocultarAviso() {
                clearTimeout(avisoTimer);
                mensaje.textContent = '';
                mensaje.className = 'anamnesis-message';
            }
            function avisar(texto, tipo = 'ok') {
                clearTimeout(avisoTimer);
                mensaje.textContent = texto;
                mensaje.className = 'anamnesis-message show ' + tipo;
                avisoTimer = setTimeout(ocultarAviso, tipo === 'error' ? 8000 : 4300);
            }
            mensaje.style.cursor = 'pointer';
            mensaje.title = 'Haz clic para cerrar esta notificación';
            mensaje.addEventListener('click', ocultarAviso);
            // El éxito solo se anuncia cuando Laravel confirma que guardó el JSON.
            function confirmarGuardado(exito, error) {
                const promesa = window.Ficha4toBridge?.flush?.();
                if (!promesa) return avisar('Cambios guardados solamente en esta sesión.', 'error');
                Promise.resolve(promesa).then(() => avisar(exito)).catch(fallo =>
                    avisar(error + (fallo?.message ? ': ' + fallo.message : ''), 'error'));
            }

            function actualizarOtro() {
                const esOtro = $('tipo').value === 'Otro';
                $('otroWrap').hidden = !esOtro;
                $('otro').required = esOtro;
                if (!esOtro) $('otro').value = '';
            }

            function sincronizarProcedimientos() {
                const seleccionado = $('tipo').value;
                document.querySelectorAll('.proc-shortcut').forEach(boton => {
                    boton.setAttribute('aria-pressed', String(boton.dataset.procedimiento ===
                    seleccionado));
                });
                actualizarOtro();
            }
            $('tipo').addEventListener('change', sincronizarProcedimientos);
            document.querySelectorAll('.proc-shortcut').forEach(boton => {
                boton.addEventListener('click', () => {
                    $('tipo').value = boton.dataset.procedimiento;
                    sincronizarProcedimientos();
                });
            });

            function persistir() {
                sessionStorage.setItem(key, JSON.stringify(registros));
            }

            function texto(tag, contenido, clase) {
                const nodo = document.createElement(tag);
                nodo.textContent = contenido;
                if (clase) nodo.className = clase;
                return nodo;
            }

            function renderizar() {
                const lista = $('procedimientosLista');
                lista.replaceChildren();
                if (!registros.length) {
                    lista.append(texto('div', 'Todavía no hay procedimientos registrados para este caso.',
                        'proc-empty'));
                    return;
                }
                const tabla = document.createElement('table');
                tabla.className = 'proc-table';
                const cabecera = document.createElement('thead');
                const filaCabecera = document.createElement('tr');
                ['Hora', 'Procedimiento', 'Responsable', 'Acción'].forEach(titulo => filaCabecera.append(texto('th',
                    titulo)));
                cabecera.append(filaCabecera);
                const cuerpo = document.createElement('tbody');
                registros.forEach((registro, indice) => {
                    const fila = document.createElement('tr');
                    fila.append(texto('td', registro.hora || '—'));
                    fila.append(texto('td', registro.tipo === 'Otro' ? (registro.otro || 'Otro') : (registro
                        .tipo || '—')));
                    fila.append(texto('td', registro.responsable || '—'));
                    const accion = document.createElement('td');
                    const ver = texto('button', 'Ver', 'proc-view');
                    ver.type = 'button';
                    ver.setAttribute('aria-expanded', 'false');
                    ver.setAttribute('aria-label', 'Ver detalles del procedimiento ' + (indice + 1));
                    const detalles = document.createElement('tr');
                    detalles.className = 'proc-details-row';
                    detalles.hidden = true;
                    const detalleCelda = document.createElement('td');
                    detalleCelda.colSpan = 4;
                    detalleCelda.append(texto('p', 'Fecha: ' + (registro.fecha || '—')));
                    if (registro.descripcion) detalleCelda.append(texto('p', 'Descripción: ' + registro
                        .descripcion));
                    // Compatibilidad con registros temporales anteriores a la simplificación del formulario.
                    if (registro.resultado) detalleCelda.append(texto('p', 'Resultado: ' + registro
                        .resultado));
                    if (registro.observaciones) detalleCelda.append(texto('p', 'Observaciones: ' + registro
                        .observaciones));
                    const eliminar = texto('button', 'Eliminar registro', 'proc-delete');
                    eliminar.type = 'button';
                    eliminar.addEventListener('click', () => abrirModalEliminar(indice));
                    detalleCelda.append(eliminar);
                    detalles.append(detalleCelda);
                    ver.addEventListener('click', () => {
                        detalles.hidden = !detalles.hidden;
                        ver.setAttribute('aria-expanded', String(!detalles.hidden));
                        ver.textContent = detalles.hidden ? 'Ver' : 'Ocultar';
                    });
                    accion.append(ver);
                    fila.append(accion);
                    cuerpo.append(fila, detalles);
                });
                tabla.append(cabecera, cuerpo);
                lista.append(tabla);
            }
            // Confirmación de eliminación: nunca borrar hasta que el estudiante confirme.
            const deleteModal = $('deleteModal');
            let indicePendienteEliminar = null;
            let focoAnteriorEliminar = null;

            function abrirModalEliminar(indice) {
                indicePendienteEliminar = indice;
                focoAnteriorEliminar = document.activeElement;
                deleteModal.hidden = false;
                $('cancelDelete').focus();
            }

            function cerrarModalEliminar() {
                deleteModal.hidden = true;
                indicePendienteEliminar = null;
                const anterior = focoAnteriorEliminar;
                focoAnteriorEliminar = null;
                if (anterior?.isConnected) anterior.focus();
            }
            $('cancelDelete').addEventListener('click', cerrarModalEliminar);
            $('confirmDelete').addEventListener('click', () => {
                if (indicePendienteEliminar === null || !registros[indicePendienteEliminar]) {
                    cerrarModalEliminar();
                    return;
                }
                const anteriores = registros.slice();
                registros.splice(indicePendienteEliminar, 1);
                try {
                    persistir();
                    cerrarModalEliminar();
                    renderizar();
                    avisar('Procedimiento eliminado.');
                } catch (error) {
                    registros = anteriores;
                    cerrarModalEliminar();
                    avisar('No fue posible eliminar el procedimiento.', 'error');
                }
            });
            deleteModal.addEventListener('click', event => {
                if (event.target === deleteModal) cerrarModalEliminar();
            });
            document.addEventListener('keydown', event => {
                if (deleteModal.hidden) return;
                if (event.key === 'Escape') {
                    event.preventDefault();
                    cerrarModalEliminar();
                }
                if (event.key === 'Tab') {
                    const botones = [$('cancelDelete'), $('confirmDelete')];
                    const indice = botones.indexOf(document.activeElement);
                    if (event.shiftKey && indice === 0) {
                        event.preventDefault();
                        botones[1].focus();
                    } else if (!event.shiftKey && indice === 1) {
                        event.preventDefault();
                        botones[0].focus();
                    }
                }
            });
            renderizar();
            form.addEventListener('submit', event => {
                event.preventDefault();
                ocultarAviso();
                actualizarOtro();
                for (const campo of form.querySelectorAll('[required]')) {
                    if (!campo.checkValidity()) {
                        campo.focus();
                        avisar('Completa los campos obligatorios antes de guardar.', 'error');
                        return;
                    }
                }
                const datos = {};
                form.querySelectorAll('[name]').forEach(campo => {
                    datos[campo.name] = campo.value.trim();
                });
                if (nombreEstudianteSesion) datos.responsable = nombreEstudianteSesion;
                const anteriores = registros.slice();
                registros.push(datos);
                try {
                    persistir();
                    renderizar();
                    form.reset();
                    restaurarResponsable();
                    sincronizarProcedimientos();
                    confirmarGuardado('Procedimiento registrado correctamente.', 'No se pudo guardar el procedimiento en el servidor');
                } catch (error) {
                    registros = anteriores;
                    avisar('No fue posible guardar el procedimiento en esta sesión.', 'error');
                }
            });
            const modal = $('clearModal');
            let focoAnterior = null;

            function cerrarModal() {
                modal.hidden = true;
                focoAnterior?.focus();
            }
            $('clearButton').addEventListener('click', () => {
                focoAnterior = document.activeElement;
                modal.hidden = false;
                $('cancelClear').focus();
            });
            $('cancelClear').addEventListener('click', cerrarModal);
            $('confirmClear').addEventListener('click', () => {
                form.reset();
                restaurarResponsable();
                sincronizarProcedimientos();
                ocultarAviso();
                cerrarModal();
            });
            modal.addEventListener('click', event => {
                if (event.target === modal) cerrarModal();
            });
            document.addEventListener('keydown', event => {
                if (modal.hidden) return;
                if (event.key === 'Escape') cerrarModal();
                if (event.key === 'Tab') {
                    const botones = [$('cancelClear'), $('confirmClear')];
                    const indice = botones.indexOf(document.activeElement);
                    if (event.shiftKey && indice === 0) {
                        event.preventDefault();
                        botones[1].focus();
                    } else if (!event.shiftKey && indice === 1) {
                        event.preventDefault();
                        botones[0].focus();
                    }
                }
            });
            $('cancelButton').addEventListener('click', () => {
                location.href = rutas.examen;
            });
        });
    </script>

</body>

</html>
