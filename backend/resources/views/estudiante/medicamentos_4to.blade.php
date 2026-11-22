<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medicamentos | Ficha Clínica TP</title>

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

        .med-check-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            line-height: 1.4
        }

        .med-check-label input {
            width: 17px;
            height: 17px;
            accent-color: #0968bd;
            flex: none
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

        /* Medicamentos: diseño consistente con Procedimientos */
        .med-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start
        }

        .med-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .med-full {
            grid-column: 1/-1
        }

        .med-fields .ef-field:last-child {
            grid-column: 1/-1
        }

        .med-fields .ef-field input:not([type=checkbox]),
        .med-fields .ef-field select,
        .med-fields .ef-field textarea {
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

        .med-fields .ef-field input:focus,
        .med-fields .ef-field select:focus,
        .med-fields .ef-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .med-fields .ef-field input[readonly] {
            background: #f6f9fc
        }

        /* Ocultar Especificar vía salvo cuando se selecciona Otra. */
        .med-fields .ef-field[hidden] {
            display: none !important
        }

        .med-actions {
            margin-top: 18px
        }

        @media(max-width:1050px) {
            .med-layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {
            .med-fields {
                grid-template-columns: 1fr
            }

            .med-actions {
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

                    <span class="topbar-title">Medicamentos</span>
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
                        <h1>Medicamentos del paciente</h1>
                        <p class="muted">
                            Registro de medicamentos administrados durante la atención del caso clínico simulado.
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
                        <small>Paciente simulado · Medicamentos</small>
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
                    <a id="tabProcedimientos"
                        href="{{ route('preview.estudiante.procedimientos') }}">Procedimientos</a>
                    <a class="selected" id="tabMedicamentos" href="{{ route('preview.estudiante.medicamentos_4to') }}"
                        aria-current="page">Medicamentos</a>
                    <a id="tabExamenes" href="{{ route('preview.estudiante.examenes') }}">Exámenes</a>
                    <a id="tabAccEnfermeria" href="#">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </nav>

                <!-- Medicamentos de 4.º medio: prototipo. El backend debe validar indicación, dosis y usuario autenticado. -->
                <form id="medicamentosForm" novalidate>
                    <div class="med-layout">
                        <section class="ef-panel" aria-labelledby="registroTitle">
                            <h2 class="ef-title" id="registroTitle">Registro de medicamento</h2>
                            <p class="ef-hint">Registra la administración indicada para el paciente simulado. Los
                                campos con * son obligatorios.</p>
                            <div class="med-fields">
                                <div class="ef-field med-full"><label for="medicamento">Nombre del medicamento
                                        *</label><input type="text" id="medicamento" name="medicamento"
                                        maxlength="120" required placeholder="Ej.: Paracetamol"></div>
                                <div class="ef-field"><label for="dosis">Dosis administrada *</label><input
                                        type="text" id="dosis" name="dosis" maxlength="80" required
                                        placeholder="Ej.: 500 mg"></div>
                                <div class="ef-field"><label for="via">Vía de administración *</label><select
                                        id="via" name="via" required>
                                        <option value="">Seleccionar vía</option>
                                        <option>Oral</option>
                                        <option>Intravenosa</option>
                                        <option>Intramuscular</option>
                                        <option>Subcutánea</option>
                                        <option>Sublingual</option>
                                        <option>Tópica</option>
                                        <option>Inhalatoria</option>
                                        <option>Rectal</option>
                                        <option>Otra</option>
                                    </select></div>
                                <div class="ef-field med-full" id="otraViaWrap" hidden><label
                                        for="otraVia">Especificar vía *</label><input type="text" id="otraVia"
                                        name="otraVia" maxlength="80" placeholder="Indica la vía utilizada"></div>
                                <div class="ef-field"><label for="fecha">Fecha *</label><input type="date"
                                        id="fecha" name="fecha" required></div>
                                <div class="ef-field"><label for="hora">Hora *</label><input type="time"
                                        id="hora" name="hora" required></div>
                                <!-- BACKEND (Laravel): obtener el nombre del estudiante desde Auth::user()->name.
                                     Al guardar en MySQL, asociar estudiante_id = Auth::id() en el controlador.
                                     Nunca confiar en un identificador o nombre enviado desde el navegador.
                                     Sin autenticación, se habilita edición únicamente para probar la vista previa. -->
                                <div class="ef-field med-full"><label for="responsable">Responsable (estudiante)
                                        *</label><input type="text" id="responsable" name="responsable"
                                        maxlength="120" required value="{{ auth()->user()?->name ?? '' }}"
                                        @auth readonly @endauth
                                        placeholder="{{ auth()->check() ? '' : 'Vista previa: ingresa un nombre para probar' }}">
                                </div>
                                <div class="ef-field med-full"><label for="observaciones">Observaciones
                                        (opcional)</label>
                                    <textarea id="observaciones" name="observaciones" maxlength="1500"
                                        placeholder="Registra observaciones pertinentes a la administración..."></textarea>
                                </div>
                                <!-- BACKEND (Laravel): guardar educacion_confirmada como booleano
                                     asociado al medicamento, caso clínico y estudiante autenticado.
                                     Una casilla sin marcar no se envía en FormData: interpretar como false. -->
                                <div class="ef-field med-full"><label class="med-check-label"
                                        for="educacionConfirmada"><input type="checkbox" id="educacionConfirmada"
                                            name="educacion_confirmada" value="1"> Confirmo que se realizó la
                                        educación al paciente</label></div>
                            </div>
                            <div id="saveMessage" class="anamnesis-message" role="status" aria-live="polite"></div>
                            <div class="ef-actions med-actions">
                                <button type="button" class="anamnesis-button" id="clearButton">Limpiar</button>
                                <button type="button" class="anamnesis-button" id="cancelButton">Cancelar</button>
                                <button type="submit" class="anamnesis-button primary">Guardar medicamento</button>
                            </div>
                        </section>
                        <section class="ef-panel" aria-labelledby="listadoTitle">
                            <h2 class="ef-title proc-title-secondary" id="listadoTitle">Medicamentos registrados</h2>
                            <div id="medicamentosLista" class="proc-list" aria-live="polite"></div>
                        </section>
                    </div>
                </form>
            </main>
        </div>
    </div>


    <div class="modal-overlay" id="clearModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="clearModalTitle">
            <div class="modal-icon">!</div>
            <h2 id="clearModalTitle">Limpiar formulario</h2>
            <p>¿Estás seguro de que deseas limpiar los campos del medicamento actual? Los medicamentos ya registrados se
                conservarán.</p>
            <div class="modal-actions"><button type="button" class="modal-cancel"
                    id="cancelClear">Cancelar</button><button type="button" class="modal-confirm"
                    id="confirmClear">Sí, limpiar</button></div>
        </div>
    </div>
    <div class="modal-overlay" id="deleteModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
            <div class="modal-icon">!</div>
            <h2 id="deleteModalTitle">Eliminar medicamento</h2>
            <p>¿Estás seguro de que deseas eliminar este medicamento registrado? Esta acción no se puede deshacer.</p>
            <div class="modal-actions"><button type="button" class="modal-cancel"
                    id="cancelDelete">Cancelar</button><button type="button" class="modal-confirm"
                    id="confirmDelete">Sí, eliminar</button></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';
            const $ = id => document.getElementById(id);
            const form = $('medicamentosForm');
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };
            const solicitado = new URLSearchParams(location.search).get('caso');
            const casoId = window.__FICHA_4TO?.codigo || (Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001');
            const query = '?caso=' + encodeURIComponent(casoId);
            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = window.__FICHA_4TO?.titulo || casos[casoId] || 'Caso clínico';
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}/" + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + query,
                triage: "{{ route('preview.estudiante.triage') }}" + query,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + query,
                abcde: "{{ route('preview.estudiante.abcde') }}" + query,
                anamnesis: "{{ route('preview.estudiante.anamnesis') }}" + query,
                examen: "{{ route('preview.estudiante.examen-fisico') }}" + query,
                procedimientos: "{{ route('preview.estudiante.procedimientos') }}" + query,
                medicamentos: "{{ route('preview.estudiante.medicamentos_4to') }}" + query
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
                tabMedicamentos: rutas.medicamentos,
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle')?.addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });
            const nombreSesion = @json(auth()->user()?->name ?? '');
            const storageKey = 'fctp_medicamentos_v1_' + casoId;
            let registros = [];
            try {
                const saved = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
                if (Array.isArray(saved)) registros = saved;
            } catch (e) {
                console.warn('No se pudieron recuperar medicamentos', e);
            }
            const mensaje = $('saveMessage');

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

            function restaurarResponsable() {
                if (nombreSesion) $('responsable').value = nombreSesion;
            }

            function actualizarVia() {
                const otra = $('via').value === 'Otra';
                $('otraViaWrap').hidden = !otra;
                $('otraVia').required = otra;
                if (!otra) $('otraVia').value = '';
            }
            $('via').addEventListener('change', actualizarVia);

            function texto(tag, contenido, clase) {
                const el = document.createElement(tag);
                el.textContent = contenido;
                if (clase) el.className = clase;
                return el;
            }

            function render() {
                const cont = $('medicamentosLista');
                cont.replaceChildren();
                if (!registros.length) {
                    cont.append(texto('div', 'Todavía no hay medicamentos registrados para este caso.',
                        'proc-empty'));
                    return;
                }
                const tabla = document.createElement('table');
                tabla.className = 'proc-table';
                const thead = document.createElement('thead'),
                    trh = document.createElement('tr');
                ['Hora', 'Medicamento', 'Responsable', 'Acción'].forEach(t => trh.append(texto('th', t)));
                thead.append(trh);
                tabla.append(thead);
                const tbody = document.createElement('tbody');
                registros.forEach((r, i) => {
                    const tr = document.createElement('tr');
                    [r.hora, r.medicamento, r.responsable].forEach(t => tr.append(texto('td', t)));
                    const action = document.createElement('td'),
                        ver = document.createElement('button');
                    ver.type = 'button';
                    ver.className = 'proc-view';
                    ver.textContent = 'Ver';
                    ver.setAttribute('aria-expanded', 'false');
                    action.append(ver);
                    tr.append(action);
                    tbody.append(tr);
                    const det = document.createElement('tr');
                    det.className = 'proc-details-row';
                    det.hidden = true;
                    const celda = document.createElement('td');
                    celda.colSpan = 4;
                    [
                        ['Fecha', r.fecha],
                        ['Dosis', r.dosis],
                        ['Vía', r.via === 'Otra' ? r.otraVia : r.via],
                        ['Observaciones', r.observaciones || 'Sin observaciones'],
                        ['Educación confirmada', r.educacion_confirmada === '1' ? 'Sí' : 'No']
                    ].forEach(([label, val]) => {
                        const line = document.createElement('p');
                        const bold = document.createElement('strong');
                        bold.textContent = label + ': ';
                        line.append(bold, document.createTextNode(val || '—'));
                        celda.append(line);
                    });
                    const borrar = document.createElement('button');
                    borrar.type = 'button';
                    borrar.className = 'proc-delete';
                    borrar.textContent = 'Eliminar registro';
                    borrar.addEventListener('click', () => abrirModal('deleteModal', i));
                    celda.append(borrar);
                    det.append(celda);
                    tbody.append(det);
                    ver.addEventListener('click', () => {
                        det.hidden = !det.hidden;
                        ver.setAttribute('aria-expanded', String(!det.hidden));
                    });
                });
                tabla.append(tbody);
                cont.append(tabla);
            }
            let focoAnterior = null,
                indiceEliminar = null;

            function abrirModal(id, indice = null) {
                focoAnterior = document.activeElement;
                indiceEliminar = indice;
                $(id).hidden = false;
                $(id).querySelector('.modal-cancel').focus();
            }

            function cerrarModal(id) {
                $(id).hidden = true;
                indiceEliminar = null;
                focoAnterior?.focus();
            }
            $('clearButton').addEventListener('click', () => abrirModal('clearModal'));
            $('cancelClear').addEventListener('click', () => cerrarModal('clearModal'));
            $('confirmClear').addEventListener('click', () => {
                form.reset();
                restaurarResponsable();
                actualizarVia();
                ocultarAviso();
                cerrarModal('clearModal');
            });
            $('cancelDelete').addEventListener('click', () => cerrarModal('deleteModal'));
            $('confirmDelete').addEventListener('click', () => {
                if (indiceEliminar !== null) {
                    const nuevos = registros.filter((_, i) => i !== indiceEliminar);
                    try {
                        sessionStorage.setItem(storageKey, JSON.stringify(nuevos));
                        registros = nuevos;
                        render();
                        avisar('Medicamento eliminado.');
                    } catch (e) {
                        avisar('No fue posible eliminar el medicamento.', 'error');
                    }
                }
                cerrarModal('deleteModal');
            });
            ['clearModal', 'deleteModal'].forEach(id => $(id).addEventListener('click', e => {
                if (e.target === $(id)) cerrarModal(id);
            }));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    ['deleteModal', 'clearModal'].forEach(id => {
                        if (!$(id).hidden) cerrarModal(id);
                    });
                }
            });
            $('cancelButton').addEventListener('click', () => {
                location.href = rutas.procedimientos;
            });
            form.addEventListener('submit', e => {
                e.preventDefault();
                actualizarVia();
                if (!form.reportValidity()) return;
                const datos = Object.fromEntries(new FormData(form).entries());
                datos.educacion_confirmada = $('educacionConfirmada').checked ? '1' : '0';
                datos.medicamento = datos.medicamento.trim();
                datos.dosis = datos.dosis.trim();
                datos.responsable = (nombreSesion || datos.responsable || '').trim();
                if (!datos.medicamento || !datos.dosis || !datos.responsable) {
                    avisar('Completa los campos obligatorios.', 'error');
                    return;
                }
                // BACKEND: validar que el medicamento y la dosis correspondan a la indicación del caso;
                // registrar usuario autenticado con Auth::id(), no confiar en los datos del cliente.
                const nuevos = [...registros, datos];
                try {
                    sessionStorage.setItem(storageKey, JSON.stringify(nuevos));
                    registros = nuevos;
                    render();
                    form.reset();
                    restaurarResponsable();
                    actualizarVia();
                    confirmarGuardado('Medicamento registrado correctamente.', 'No se pudo guardar el medicamento');
                } catch (error) {
                    avisar('No fue posible guardar el medicamento.', 'error');
                }
            });
            restaurarResponsable();
            actualizarVia();
            render();
        });
    </script>
<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>

</html>
