<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anamnesis | Ficha Clínica TP</title>

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

                    <span class="topbar-title">Anamnesis</span>
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
                        <h1>Anamnesis del paciente</h1>
                        <p class="muted">
                            Registro de antecedentes y motivo de consulta
                            del paciente simulado.
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
                        <small>Paciente simulado · Anamnesis</small>
                    </div>

                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- Navegación entre etapas -->
                <nav class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                    <a id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                    <a id="tabSignos" href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a>
                    <a id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}" aria-current="page">ABCDE</a>
                    <a class="selected" id="tabAnamnesis"
                        href="{{ route('preview.estudiante.anamnesis') }}">Anamnesis</a>
                    <a id="tabExaFisico" href="{{ route('preview.estudiante.examen-fisico') }}">Examen físico</a>
                    <a id="tabProcedimientos" href="#">Procedimientos</a>
                    <a id="tabMedicamentos" href="#">Medicamentos</a>
                    <a id="tabExamenes" href="#">Exámenes</a>
                    <a id="tabAccEnfermeria" href="#">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </nav>

                <!-- Formulario -->
                <form id="anamnesisForm" novalidate>
                    <div class="anamnesis-container">
                        <!-- Primera fila -->
                        <section class="anamnesis-card wide">
                            <div class="anamnesis-header rosa">
                                <h2>Motivo de consulta</h2>
                            </div>
                            <div class="anamnesis-field"><label for="motivoConsulta">Motivo de consulta *</label>
                                <textarea id="motivoConsulta" name="motivoConsulta" maxlength="1500" required
                                    placeholder="Describe el motivo principal de consulta..."></textarea>
                            </div>
                        </section>
                        <section class="anamnesis-card wide">
                            <div class="anamnesis-header azul">
                                <h2>Enfermedad actual</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="¿Refiere enfermedad actual?">
                                <label><input type="radio" name="refiereEnfermedadActual" value="si" checked> Sí refiere</label>
                                <label><input type="radio" name="refiereEnfermedadActual" value="no"> No refiere</label>
                            </div>
                            <div class="anamnesis-field"><label for="enfermedadActual">Descripción de la enfermedad
                                    actual <span id="enfermedadActualRequired">*</span></label>
                                <textarea id="enfermedadActual" name="enfermedadActual" maxlength="3000" required
                                    placeholder="Describe la enfermedad actual, los síntomas y su evolución..."></textarea>
                                <small>Solo es obligatorio cuando seleccionas «Sí refiere».</small>
                            </div>
                        </section>
                        <!-- Segunda fila -->
                        <section class="anamnesis-card">
                            <div class="anamnesis-header naranja">
                                <h2>Alergias</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="Alergias">
                                <label><input type="radio" name="alergias" value="no" checked> No refiere
                                    alergias</label>
                                <label><input type="radio" name="alergias" value="si"> Sí refiere
                                    alergias</label>
                            </div>
                            <div class="anamnesis-field" id="detalleAlergiasWrap"><label
                                    for="detalleAlergias">Especificar alergias</label><input id="detalleAlergias"
                                    name="detalleAlergias" maxlength="1500" placeholder="Indica alergias conocidas">
                            </div>
                        </section>
                        <section class="anamnesis-card">
                            <div class="anamnesis-header lila">
                                <h2>Medicamentos</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="Medicamentos">
                                <label><input type="radio" name="medicamentos" value="no" checked> No utiliza
                                    medicamentos</label>
                                <label><input type="radio" name="medicamentos" value="si"> Sí utiliza
                                    medicamentos</label>
                            </div>
                            <div class="anamnesis-field" id="detalleMedicamentosWrap"><label
                                    for="detalleMedicamentos">Especificar medicamentos</label><input
                                    id="detalleMedicamentos" name="detalleMedicamentos" maxlength="1500"
                                    placeholder="Nombre, dosis y frecuencia"></div>
                        </section>
                        <section class="anamnesis-card">
                            <div class="anamnesis-header verde">
                                <h2>Antecedentes mórbidos</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="Antecedentes mórbidos">
                                <label><input type="radio" name="antecedentesMorbidos" value="no" checked> No
                                    refiere antecedentes</label>
                                <label><input type="radio" name="antecedentesMorbidos" value="si"> Sí refiere
                                    antecedentes</label>
                            </div>
                            <div class="anamnesis-field" id="detalleMorbidosWrap"><label
                                    for="detalleMorbidos">Especificar antecedentes</label><input id="detalleMorbidos"
                                    name="detalleMorbidos" maxlength="1500" placeholder="Antecedentes relevantes">
                            </div>
                        </section>
                        <!-- Tercera fila -->
                        <section class="anamnesis-card">
                            <div class="anamnesis-header celeste">
                                <h2>Antecedentes quirúrgicos</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="Antecedentes quirúrgicos">
                                <label><input type="radio" name="antecedentesQuirurgicos" value="no" checked>
                                    No refiere antecedentes</label>
                                <label><input type="radio" name="antecedentesQuirurgicos" value="si"> Sí
                                    refiere antecedentes</label>
                            </div>
                            <div class="anamnesis-field" id="detalleQuirurgicosWrap"><label
                                    for="detalleQuirurgicos">Especificar cirugías</label><input
                                    id="detalleQuirurgicos" name="detalleQuirurgicos" maxlength="1500"
                                    placeholder="Cirugías previas"></div>
                        </section>
                        <section class="anamnesis-card">
                            <div class="anamnesis-header rosado">
                                <h2>Antecedentes familiares</h2>
                            </div>
                            <div class="anamnesis-options" role="radiogroup" aria-label="Antecedentes familiares">
                                <label><input type="radio" name="familiaresEstado" value="no" checked> No
                                    refiere antecedentes</label>
                                <label><input type="radio" name="familiaresEstado" value="si"> Sí refiere
                                    antecedentes</label>
                            </div>
                            <div class="anamnesis-field" id="detalleFamiliaresWrap"><label
                                    for="antecedentesFamiliares">Especificar antecedentes</label><input
                                    id="antecedentesFamiliares" name="antecedentesFamiliares" maxlength="1500"
                                    placeholder="Antecedentes familiares relevantes"></div>
                        </section>
                        <section class="anamnesis-card">
                            <div class="anamnesis-header menta">
                                <h2>Hábitos</h2>
                            </div>
                            <div class="anamnesis-habits">
                                <div class="anamnesis-field"><label for="tabaco">Tabaquismo</label><select
                                        id="tabaco" name="tabaco">
                                        <option value="No">No</option>
                                        <option>Sí</option>
                                        <option>Exfumador</option>
                                        <option>No informado</option>
                                    </select></div>
                                <div class="anamnesis-field"><label for="alcohol">Alcohol</label><select
                                        id="alcohol" name="alcohol">
                                        <option value="No">No</option>
                                        <option>Ocasional</option>
                                        <option>Frecuente</option>
                                        <option>No informado</option>
                                    </select></div>
                                <div class="anamnesis-field"><label for="drogas">Drogas</label><select
                                        id="drogas" name="drogas">
                                        <option value="No">No</option>
                                        <option>Sí</option>
                                        <option>No informado</option>
                                    </select></div>
                                <div class="anamnesis-field"><label for="actividadFisica">Actividad
                                        física</label><select id="actividadFisica" name="actividadFisica">
                                        <option>Regular</option>
                                        <option>Sedentaria</option>
                                        <option>Ocasional</option>
                                        <option>No informado</option>
                                    </select></div>
                                <!-- Riesgo de caída solicitado en la reunión del 15/10/2026 -->
                                <div class="anamnesis-field"><label for="riesgoCaida">Riesgo de caída *</label><select
                                        id="riesgoCaida" name="riesgoCaida" required>
                                        <option value="">Seleccionar</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select></div>
                                <div class="anamnesis-field"><label for="alimentacion">Alimentación</label><select
                                        id="alimentacion" name="alimentacion">
                                        <option>Equilibrada</option>
                                        <option>Alta en grasas y carbohidratos</option>
                                        <option>Vegetariana</option>
                                        <option>Otra</option>
                                        <option>No informado</option>
                                    </select></div>
                            </div>
                            <div class="anamnesis-footer">
                                <div id="saveMessage" class="anamnesis-message" role="status" aria-live="polite">
                                </div>
                                <button type="button" class="anamnesis-button" id="clearButton">Limpiar</button>
                                <button type="button" class="anamnesis-button" id="cancelButton">Cancelar</button>
                                <button type="submit" class="anamnesis-button primary" id="saveButton">Guardar
                                    anamnesis</button>
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
            <p id="clearModalDescription">¿Estás seguro de que deseas limpiar los datos ingresados en Anamnesis? Se
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
            const form = $('anamnesisForm');
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };
            const solicitado = new URLSearchParams(window.location.search).get('caso');
            const casoId = window.__FICHA_4TO?.codigo || (Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001');
            const casoQuery = '?caso=' + encodeURIComponent(casoId);
            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = window.__FICHA_4TO?.titulo || casos[casoId] || 'Caso clínico';
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}" + '/' + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + casoQuery,
                triage: "{{ route('preview.estudiante.triage') }}" + casoQuery,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + casoQuery,
                abcde: "{{ route('preview.estudiante.abcde') }}" + casoQuery,
                anamnesis: "{{ route('preview.estudiante.anamnesis') }}" + casoQuery
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
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle').addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });
            const grupos = [
                ['alergias', 'detalleAlergias'],
                ['medicamentos', 'detalleMedicamentos'],
                ['antecedentesMorbidos', 'detalleMorbidos'],
                ['antecedentesQuirurgicos', 'detalleQuirurgicos'],
                ['familiaresEstado', 'antecedentesFamiliares']
            ];

            function actualizarCampos() {
                const refiereEnfermedad = form.querySelector('input[name="refiereEnfermedadActual"]:checked')?.value !== 'no';
                const descripcionEnfermedad = $('enfermedadActual');
                descripcionEnfermedad.disabled = !refiereEnfermedad;
                descripcionEnfermedad.required = refiereEnfermedad;
                $('enfermedadActualRequired').hidden = !refiereEnfermedad;
                if (!refiereEnfermedad) descripcionEnfermedad.value = '';
                grupos.forEach(([nombre, id]) => {
                    const activo = form.querySelector('input[name="' + nombre + '"]:checked')?.value ===
                        'si';
                    const campo = $(id);
                    campo.required = activo;
                    campo.disabled = !activo;
                    campo.placeholder = activo ? campo.placeholder : 'Selecciona Sí para especificar';
                    if (!activo) campo.value = '';
                });
            }

            function mensaje(texto, tipo = 'ok') {
                $('saveMessage').textContent = texto;
                $('saveMessage').className = 'anamnesis-message show ' + tipo;
            }

            function ocultarMensaje() {
                $('saveMessage').className = 'anamnesis-message';
                $('saveMessage').textContent = '';
            }
            const storageKey = 'fctp_anamnesis_v1_' + casoId;

            function obtenerDatos() {
                const datos = {};
                form.querySelectorAll('[name]').forEach(campo => {
                    if (campo.type === 'radio') {
                        if (campo.checked) datos[campo.name] = campo.value;
                    } else datos[campo.name] = campo.value.trim();
                });
                return datos;
            }

            function cargarDatos() {
                try {
                    const registro = JSON.parse(sessionStorage.getItem(storageKey) || 'null');
                    if (!registro?.datos) return;
                    Object.entries(registro.datos).forEach(([nombre, valor]) => {
                        const campos = Array.from(form.querySelectorAll('[name]')).filter(c => c.name ===
                            nombre);
                        campos.forEach(c => {
                            if (c.type === 'radio') c.checked = (c.value === valor);
                            else c.value = valor;
                        });
                    });
                    actualizarCampos();
                } catch (error) {
                    console.warn('No se pudo recuperar la anamnesis.', error);
                }
            }
            form.addEventListener('change', event => {
                if (event.target.type === 'radio') actualizarCampos();
                ocultarMensaje();
            });
            form.addEventListener('submit', async event => {
                event.preventDefault();
                ocultarMensaje();
                if (!form.checkValidity()) {
                    const invalido = form.querySelector(':invalid');
                    if (invalido) invalido.focus();
                    mensaje('Completa los campos obligatorios antes de guardar.', 'error');
                    return;
                }
                try {
                    sessionStorage.setItem(storageKey, JSON.stringify({
                        casoId,
                        fechaActualizacion: new Date().toISOString(),
                        datos: obtenerDatos()
                    }));
                    // En los casos reales, el puente guarda la sección en Laravel.
                    // Mostrar éxito solo después de confirmar la respuesta del servidor.
                    if (window.__FICHA_4TO) {
                        if (!window.Ficha4toBridge?.flush) throw new Error('La conexión con el servidor no está disponible.');
                        mensaje('Guardando anamnesis…');
                        await window.Ficha4toBridge.flush();
                        mensaje('Anamnesis guardada correctamente.');
                    } else {
                        mensaje('Anamnesis guardada en esta sesión de demostración.');
                    }
                } catch (error) {
                    mensaje('No fue posible guardar la anamnesis: ' + (error.message || 'inténtalo nuevamente.'), 'error');
                }
            });
            // Modal de confirmación antes de limpiar el formulario
            const clearModal = $('clearModal');
            const cancelClear = $('cancelClear');
            const confirmClear = $('confirmClear');
            let previousFocus = null;

            function cerrarModalLimpiar() {
                clearModal.hidden = true;
                document.body.style.overflow = '';
                if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus();
            }

            $('clearButton').addEventListener('click', () => {
                previousFocus = document.activeElement;
                clearModal.hidden = false;
                document.body.style.overflow = 'hidden';
                cancelClear.focus();
            });

            cancelClear.addEventListener('click', cerrarModalLimpiar);
            confirmClear.addEventListener('click', () => {
                form.reset();
                actualizarCampos();
                ocultarMensaje();
                cerrarModalLimpiar();
            });
            clearModal.addEventListener('click', event => {
                if (event.target === clearModal) cerrarModalLimpiar();
            });
            document.addEventListener('keydown', event => {
                if (clearModal.hidden) return;
                if (event.key === 'Escape') {
                    event.preventDefault();
                    cerrarModalLimpiar();
                } else if (event.key === 'Tab') {
                    if (event.shiftKey && document.activeElement === cancelClear) {
                        event.preventDefault();
                        confirmClear.focus();
                    } else if (!event.shiftKey && document.activeElement === confirmClear) {
                        event.preventDefault();
                        cancelClear.focus();
                    }
                }
            });
            $('cancelButton').addEventListener('click', () => {
                window.Ficha4toBridge?.flush().then(() => { window.location.href = rutas.abcde; }).catch(() => alert('No se guardó el registro en el servidor.'));
            });
            actualizarCampos();
            cargarDatos();
        });
    </script>

<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>

</html>
