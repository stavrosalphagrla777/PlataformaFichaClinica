<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Examen físico | Ficha Clínica TP</title>

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

        .ef-teacher-reference {
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 12px;
            margin-bottom: 7px;
            border: 1px solid #bfd8f1;
            border-left: 4px solid #1766aa;
            border-radius: 8px;
            background: #eff6ff;
        }

        .ef-teacher-reference label {
            color: #164775;
            font-size: 13px;
            font-weight: 750;
        }

        .ef-teacher-reference textarea[readonly] {
            min-height: 72px;
            background: #f8fbff;
            color: #273e55;
            border-color: #c8dcef;
            cursor: default;
            white-space: pre-wrap;
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

                    <span class="topbar-title">Examen físico</span>
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
                        <h1>Examen físico del paciente</h1>
                        <p class="muted">
                            Registro del examen físico general y segmentario del paciente simulado.
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
                        <small>Paciente simulado · Examen físico</small>
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
                    <a class="selected" id="tabExaFisico" href="{{ route('preview.estudiante.examen-fisico') }}"
                        aria-current="page">Examen físico</a>
                    <a id="tabProcedimientos" href="{{ route('preview.estudiante.procedimientos') }}">Procedimientos</a>
                    <a id="tabMedicamentos" href="#">Medicamentos</a>
                    <a id="tabExamenes" href="#">Exámenes</a>
                    <a id="tabAccEnfermeria" href="#">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </nav>

                <!-- Examen físico de 4.º medio: general y segmentario -->
                <form id="examenFisicoForm" novalidate>
                    <div class="ef-columns">
                        <section class="ef-panel" aria-labelledby="generalTitle">
                            <h2 class="ef-title" id="generalTitle">Examen físico general</h2>
                            <div class="ef-fields">
                                <div class="ef-field">
                                    <label for="conciencia">Estado de conciencia *</label>
                                    <select id="conciencia" name="conciencia" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Alerta">Alerta</option>
                                        <option value="Somnoliento">Somnoliento</option>
                                        <option value="Responde a la voz">Responde a la voz</option>
                                        <option value="Responde al dolor">Responde al dolor</option>
                                        <option value="No responde">No responde</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select>
                                </div>
                                <div class="ef-field">
                                    <label for="marcha">Marcha *</label>
                                    <select id="marcha" name="marcha" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Estable con apoyo">Estable con apoyo</option>
                                        <option value="Inestable">Inestable</option>
                                        <option value="No deambula">No deambula</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select>
                                </div>
                                <div class="ef-field">
                                    <label for="facie">Facie *</label>
                                    <select id="facie" name="facie" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Sin alteraciones">Sin alteraciones</option>
                                        <option value="Dolorosa">Dolorosa</option>
                                        <option value="Ansiosa">Ansiosa</option>
                                        <option value="Pálida">Pálida</option>
                                        <option value="Otra">Otra</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select>
                                </div>
                                <div class="ef-field">
                                    <label for="hidratacion">Hidratación *</label>
                                    <select id="hidratacion" name="hidratacion" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Adecuada">Adecuada</option>
                                        <option value="Leve deshidratación">Leve deshidratación</option>
                                        <option value="Deshidratación moderada">Deshidratación moderada</option>
                                        <option value="Deshidratación severa">Deshidratación severa</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select>
                                </div>
                                <div class="ef-field">
                                    <label for="eva">Dolor / EVA (opcional)</label>
                                    <input type="number" id="eva" name="eva" min="0"
                                        max="10" step="1" inputmode="numeric" placeholder="Ej.: 3"
                                        aria-describedby="evaAyuda">
                                    <small id="evaAyuda">Ingresa un número entero de 0 a 10 (opcional).</small>
                                </div>
                                <div class="ef-field">
                                    <label for="observacionesGeneral">Observaciones del examen general</label>
                                    <textarea id="observacionesGeneral" name="observacionesGeneral" maxlength="2500"
                                        placeholder="Registrar hallazgos clínicos relevantes..."></textarea>
                                </div>
                            </div>
                        </section>
                        <section class="ef-panel" aria-labelledby="segmentTitle">
                            <h2 class="ef-title" id="segmentTitle">Examen físico segmentario</h2>
                            <p class="ef-hint">Consulta la información fija aportada por el docente en cada segmento y registra tus propios hallazgos y observaciones en los campos editables.</p>
                            <details class="ef-segment" open>
                                <summary><span>Cabeza</span><small>Registrar hallazgos y observaciones</small></summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_cabeza">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_cabeza" readonly rows="3" aria-label="Hallazgos del docente para Cabeza">{{ data_get($ficha4toContext ?? [], 'segmentario.cabeza_cuello', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_cabeza">Hallazgos de cabeza</label>
                                    <textarea id="hallazgo_cabeza" name="hallazgo_cabeza" maxlength="1800"
                                        placeholder="Describe los hallazgos del caso simulado..."></textarea>
                                    <label for="observacion_cabeza">Observaciones de cabeza</label>
                                    <textarea id="observacion_cabeza" name="observacion_cabeza" maxlength="1800"
                                        placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Cuello</span><small>Registrar hallazgos y observaciones</small></summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_cuello">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_cuello" readonly rows="3" aria-label="Hallazgos del docente para Cuello">{{ data_get($ficha4toContext ?? [], 'segmentario.cabeza_cuello', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_cuello">Hallazgos de cuello</label>
                                    <textarea id="hallazgo_cuello" name="hallazgo_cuello" maxlength="1800"
                                        placeholder="Describe los hallazgos del caso simulado..."></textarea>
                                    <label for="observacion_cuello">Observaciones de cuello</label>
                                    <textarea id="observacion_cuello" name="observacion_cuello" maxlength="1800"
                                        placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Tórax</span><small>Registrar hallazgos y observaciones</small></summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_torax">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_torax" readonly rows="3" aria-label="Hallazgos del docente para Tórax">{{ data_get($ficha4toContext ?? [], 'segmentario.torax_abdomen', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_torax">Hallazgos de tórax</label>
                                    <textarea id="hallazgo_torax" name="hallazgo_torax" maxlength="1800"
                                        placeholder="Describe los hallazgos del caso simulado..."></textarea>
                                    <label for="observacion_torax">Observaciones de tórax</label>
                                    <textarea id="observacion_torax" name="observacion_torax" maxlength="1800"
                                        placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Abdomen</span><small>Registrar hallazgos y observaciones</small>
                                </summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_abdomen">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_abdomen" readonly rows="3" aria-label="Hallazgos del docente para Abdomen">{{ data_get($ficha4toContext ?? [], 'segmentario.torax_abdomen', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_abdomen">Hallazgos de abdomen</label>
                                    <textarea id="hallazgo_abdomen" name="hallazgo_abdomen" maxlength="1800"
                                        placeholder="Describe los hallazgos del caso simulado..."></textarea>
                                    <label for="observacion_abdomen">Observaciones de abdomen</label>
                                    <textarea id="observacion_abdomen" name="observacion_abdomen" maxlength="1800"
                                        placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Extremidades</span><small>Registrar hallazgos y observaciones</small>
                                </summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_extremidades">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_extremidades" readonly rows="3" aria-label="Hallazgos del docente para Extremidades">{{ data_get($ficha4toContext ?? [], 'segmentario.extremidades_piel', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_extremidades">Hallazgos de extremidades</label>
                                    <textarea id="hallazgo_extremidades" name="hallazgo_extremidades" maxlength="1800"
                                        placeholder="Describe los hallazgos del caso simulado..."></textarea>
                                    <label for="observacion_extremidades">Observaciones de extremidades</label>
                                    <textarea id="observacion_extremidades" name="observacion_extremidades" maxlength="1800"
                                        placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Piel</span><small>Registrar hallazgos y observaciones</small></summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_piel">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_piel" readonly rows="3" aria-label="Hallazgos del docente para piel">{{ data_get($ficha4toContext ?? [], 'segmentario.extremidades_piel', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_piel">Hallazgos de piel</label>
                                    <textarea id="hallazgo_piel" name="hallazgo_piel" maxlength="1800" placeholder="Describe los hallazgos de piel..."></textarea>
                                    <label for="observacion_piel">Observaciones de piel</label>
                                    <textarea id="observacion_piel" name="observacion_piel" maxlength="1800" placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <details class="ef-segment">
                                <summary><span>Neurológico</span><small>Registrar hallazgos y observaciones</small></summary>
                                <div class="ef-segment-body">
                                    <div class="ef-teacher-reference" role="note">
                                        <label for="docente_neurologico">Hallazgos proporcionados por el docente (solo lectura)</label>
                                        <textarea id="docente_neurologico" readonly rows="3" aria-label="Hallazgos del docente para evaluación neurológica">{{ data_get($ficha4toContext ?? [], 'segmentario.neurologico', '') ?: 'No informado por el docente' }}</textarea>
                                    </div>
                                    <label for="hallazgo_neurologico">Hallazgos neurológicos</label>
                                    <textarea id="hallazgo_neurologico" name="hallazgo_neurologico" maxlength="1800" placeholder="Describe los hallazgos neurológicos..."></textarea>
                                    <label for="observacion_neurologico">Observaciones neurológicas</label>
                                    <textarea id="observacion_neurologico" name="observacion_neurologico" maxlength="1800" placeholder="Agrega observaciones relevantes..."></textarea>
                                </div>
                            </details>
                            <div id="saveMessage" class="anamnesis-message" role="status" aria-live="polite"></div>
                            <div class="ef-actions">
                                <button type="button" class="anamnesis-button" id="clearButton">Limpiar</button>
                                <button type="button" class="anamnesis-button" id="cancelButton">Cancelar</button>
                                <button type="submit" class="anamnesis-button primary">Guardar examen físico</button>
                            </div>
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
            <p id="clearModalDescription">¿Estás seguro de que deseas limpiar los datos ingresados en Examen físico? Se
                descartarán los cambios no guardados.</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="cancelClear">Cancelar</button>
                <button type="button" class="modal-confirm" id="confirmClear">Sí, limpiar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';
            const $ = id => document.getElementById(id);
            const form = $('examenFisicoForm');
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
                examen: "{{ route('preview.estudiante.examen-fisico') }}" + casoQuery
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
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle').addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });
            const key = 'fctp_examen_fisico_v1_' + casoId;
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

            function datosFormulario() {
                const datos = {};
                form.querySelectorAll('[name]').forEach(campo => {
                    datos[campo.name] = campo.value.trim();
                });
                return datos;
            }
            try {
                const registro = JSON.parse(sessionStorage.getItem(key) || 'null');
                if (registro?.datos) {
                    Object.entries(registro.datos).forEach(([nombre, valor]) => {
                        const campo = form.elements.namedItem(nombre);
                        if (campo && typeof valor === 'string') campo.value = valor;
                    });
                }
            } catch (error) {
                console.warn('No se pudieron recuperar los datos:', error);
            }
            form.addEventListener('submit', event => {
                event.preventDefault();
                ocultarAviso();
                const campos = form.querySelectorAll('[required]');
                for (const campo of campos) {
                    if (!campo.checkValidity()) {
                        campo.focus();
                        avisar('Completa los campos obligatorios antes de guardar.', 'error');
                        return;
                    }
                }
                // Validar que EVA sea un entero entre 0 y 10 cuando se registre.
                const eva = $('eva');
                if (eva.value.trim() !== '' && (!eva.validity.valid || !Number.isInteger(Number(eva
                    .value)))) {
                    eva.focus();
                    avisar('El dolor EVA debe ser un número entero entre 0 y 10.', 'error');
                    return;
                }
                try {
                    sessionStorage.setItem(key, JSON.stringify({
                        casoId,
                        fechaActualizacion: new Date().toISOString(),
                        datos: datosFormulario()
                    }));
                    confirmarGuardado('Examen físico guardado correctamente.', 'No se pudo guardar el examen físico en el servidor');
                } catch (error) {
                    avisar('No fue posible guardar el examen físico en esta sesión.', 'error');
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
                location.href = rutas.anamnesis;
            });
        });
    </script>

</body>

</html>
