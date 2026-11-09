<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signos vitales | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
</head>

<body>
    <div class="app app-shell" id="appShell">

        <!-- Menú lateral -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP">
                </div>
                <span>Ficha Clínica TP</span>
            </div>
            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div>
                <a class="nav-link" href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
                <div class="nav-title">Atención clínica</div>
                <a class="nav-link" id="sidebarFicha" href="#">Ficha clínica</a>
                <a class="nav-link" id="sidebarAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                <a class="nav-link" id="sidebarTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                <a class="nav-link active" id="sidebarSignos" href="{{ route('preview.estudiante.signos-vitales') }}"
                    aria-current="page">Signos vitales</a>
                <a class="nav-link" id="sidebarABCDE" href="{{ route('preview.estudiante.abcde') }}">Evaluación
                    clínica</a>
                <div class="nav-title">Cuenta</div>
                <a class="nav-link nav-disabled" href="#">Mi perfil</a>
                <a class="nav-link" href="{{ url('/login') }}">Cerrar sesión</a>
            </nav>
        </aside>

        <div class="main-area">
            <!-- Barra superior -->
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú"
                        aria-controls="sidebar" aria-expanded="true">
                        <span></span><span></span><span></span>
                    </button>
                    <span class="topbar-title">Signos vitales</span>
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
                        <h1>Signos vitales y antropometría</h1>
                        <p class="muted">Registra los parámetros clínicos del paciente simulado.</p>
                    </div>
                    <a class="btn btn-secondary" id="backToCase" href="{{ route('preview.estudiante.casos') }}">←
                        Volver al caso</a>
                </div>

                <!-- Caso clínico -->
                <div class="case-banner">
                    <div>
                        <span class="case-code" id="caseCode">CAS-001</span>
                        <strong id="caseTitle">Alteración neurológica aguda</strong>
                        <small>Paciente simulado · Registro de signos vitales</small>
                    </div>
                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- Navegación entre etapas -->
                <nav class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                    <a id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                    <a class="selected" id="tabSignos" href="{{ route('preview.estudiante.signos-vitales') }}"
                        aria-current="page">Signos vitales</a>
                    <a id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}">ABCDE</a>
                    <span>Anamnesis</span>
                    <span>Examen físico</span>
                    <span>Procedimientos</span>
                    <span>Medicamentos</span>
                    <span>Exámenes</span>
                    <span>Acciones de enfermería</span>
                    <span>Destino</span>
                </nav>

                <!-- Formulario -->
                <form id="signosForm" class="form-card" novalidate>
                    <section class="form-section">
                        <h2>1. Signos vitales</h2>
                        <p class="triage-help">Los valores se clasifican automáticamente según los rangos utilizados en
                            Triage. Los valores atípicos generan advertencias de verificación.</p>

                        <div class="form-grid cols-3 triage-grid">
                            <div class="vital-field">
                                <label>Presión arterial (mmHg) <em>*</em></label>
                                <div class="blood-pressure">
                                    <input type="number" id="presionSistolica" name="presion_sistolica" min="0"
                                        step="1" required placeholder="Sistólica 1 a 300"
                                        aria-label="Presión sistólica">
                                    <span class="pressure-divider">/</span>
                                    <input type="number" id="presionDiastolica" name="presion_diastolica"
                                        min="0" step="1" required placeholder="Diastólica 1 a 200"
                                        aria-label="Presión diastólica">
                                </div>
                                <div class="threshold-alert" id="alertPresion" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalPresion" aria-live="polite"></div>
                            </div>

                            <div class="vital-field">
                                <label for="frecuenciaCardiaca">Frecuencia cardíaca (lpm) <em>*</em></label>
                                <input type="number" id="frecuenciaCardiaca" name="frecuencia_cardiaca"
                                    min="0" step="1" required placeholder="20 a 250">
                                <div class="threshold-alert" id="alertFrecuenciaCardiaca" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalFrecuenciaCardiaca" aria-live="polite"></div>
                            </div>

                            <div class="vital-field">
                                <label for="frecuenciaRespiratoria">Frecuencia respiratoria (rpm) <em>*</em></label>
                                <input type="number" id="frecuenciaRespiratoria" name="frecuencia_respiratoria"
                                    min="0" step="1" required placeholder="5 a 60">
                                <div class="threshold-alert" id="alertFrecuenciaRespiratoria" aria-live="polite">
                                </div>
                                <div class="atypical-alert" id="atypicalFrecuenciaRespiratoria" aria-live="polite">
                                </div>
                            </div>

                            <div class="vital-field">
                                <label for="saturacion">Saturación de O₂ (%) <em>*</em></label>
                                <input type="number" id="saturacion" name="saturacion" min="0"
                                    max="100" step="1" required placeholder="50 a 100">
                                <div class="threshold-alert" id="alertSaturacion" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalSaturacion" aria-live="polite"></div>
                            </div>

                            <div class="vital-field">
                                <label for="temperatura">Temperatura axilar (°C) <em>*</em></label>
                                <input type="number" id="temperatura" name="temperatura" min="0"
                                    step="0.1" required placeholder="30.0 a 43.0">
                                <div class="threshold-alert" id="alertTemperatura" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalTemperatura" aria-live="polite"></div>
                            </div>

                            <div class="vital-field">
                                <label for="hemoglucotest">Hemoglucotest (mg/dL) <em>*</em></label>
                                <input type="number" id="hemoglucotest" name="hemoglucotest" min="0"
                                    step="1" required placeholder="20 a 600">
                                <div class="threshold-alert" id="alertHemoglucotest" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalHemoglucotest" aria-live="polite"></div>
                            </div>

                            <div class="vital-field">
                                <label for="glasgow">Glasgow <em>*</em></label>
                                <input type="number" id="glasgow" name="glasgow" min="3" max="15"
                                    step="1" required placeholder="3 a 15">
                                <div class="threshold-alert" id="alertGlasgow" aria-live="polite"></div>
                                <div class="atypical-alert" id="atypicalGlasgow" aria-live="polite"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Antropometría -->
                    <section class="form-section">
                        <h2>2. Antropometría</h2>
                        <p class="triage-help">Peso y talla son opcionales. El IMC se calcula automáticamente cuando
                            ambos valores son válidos.</p>
                        <div class="form-grid cols-3 triage-grid">
                            <div class="vital-field">
                                <label for="peso">Peso (kg) <span class="optional-label">Opcional</span></label>
                                <input type="number" id="peso" name="peso" min="0" step="any"
                                    placeholder="Opcional: 1 a 350">
                                <div class="atypical-alert" id="atypicalPeso" aria-live="polite"></div>
                            </div>
                            <div class="vital-field">
                                <label for="talla">Talla (cm) <span class="optional-label">Opcional</span></label>
                                <input type="number" id="talla" name="talla" min="0" step="any"
                                    placeholder="Opcional: 30 a 250">
                                <div class="atypical-alert" id="atypicalTalla" aria-live="polite"></div>
                            </div>
                            <div class="vital-field imc-result">
                                <label for="imc">Índice de masa corporal (IMC)</label>
                                <input type="text" id="imc" name="imc" readonly
                                    placeholder="Cálculo automático">
                                <div class="threshold-alert" id="alertIMC" aria-live="polite"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Observaciones -->
                    <section class="form-section">
                        <h2>3. Observaciones clínicas</h2>
                        <label class="triage-field">
                            <span>Interpretación del estudiante</span>
                            <textarea name="observaciones" rows="4"
                                placeholder="Registra observaciones sobre los signos vitales del paciente."></textarea>
                        </label>
                    </section>

                    <!-- Acciones del formulario -->
                    <div class="form-actions">
                        <p class="save-message" id="saveMessage" role="status" aria-live="polite"></p>
                        <button type="button" class="btn btn-light" id="clearButton">Limpiar</button>
                        <a class="btn btn-light" id="cancelButton"
                            href="{{ route('preview.estudiante.casos') }}">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="guardarRegistro">Guardar registro</button>
                    </div>
                </form>

                <!-- Historial y gráfico -->
                <section class="vitals-dashboard">
                    <div class="vitals-panel">
                        <div class="vitals-panel-header">
                            <h2>Historial de signos vitales</h2>
                            <button type="button" class="btn btn-primary" id="nuevoRegistro">+ Nuevo
                                registro</button>
                        </div>
                        <div class="vitals-table-scroll">
                            <table class="vitals-table">
                                <thead>
                                    <tr>
                                        <th>Fecha y hora</th>
                                        <th>FC</th>
                                        <th>PA</th>
                                        <th>FR</th>
                                        <th>T°</th>
                                        <th>SatO₂</th>
                                        <th>HGT</th>
                                        <th>Peso</th>
                                        <th>IMC</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="historialSignos"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="vitals-panel">
                        <div class="vitals-panel-header">
                            <h2>Evolución de signos vitales</h2>
                            <span class="vitals-period">Últimas 12 horas</span>
                        </div>
                        <div class="vitals-chart-container">
                            <canvas id="graficoSignos"></canvas>
                        </div>
                    </div>

                    <!-- Evolución de Glasgow -->
                    <div class="vitals-panel">
                        <div class="vitals-panel-header">
                            <h2>Evolución de la escala de Glasgow</h2>
                            <span class="vitals-period">Escala de 3 a 15 puntos</span>
                        </div>
                        <div class="vitals-chart-container">
                            <canvas id="graficoGlasgow" aria-label="Gráfico de evolución de Glasgow"
                                role="img"></canvas>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal-overlay" id="clearModal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="clearModalTitle">
            <div class="modal-icon">!</div>
            <h2 id="clearModalTitle">Limpiar formulario</h2>
            <p>¿Estás seguro de que deseas limpiar los datos ingresados? Se descartarán los cambios no guardados.</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="cancelClear">Cancelar</button>
                <button type="button" class="modal-confirm" id="confirmClear">Sí, limpiar</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            // Referencias generales
            const $ = id => document.getElementById(id);
            const form = $('signosForm');
            const message = $('saveMessage');
            const tbody = $('historialSignos');
            let grafico = null;
            let graficoGlasgow = null;
            let registroEditando = null;

            // Casos de demostración
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };

            const params = new URLSearchParams(window.location.search);
            const solicitado = params.get('caso');
            const casoId = Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001';
            const casoQuery = '?caso=' + encodeURIComponent(casoId);

            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = casos[casoId];

            // Rutas de navegación
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}" + '/' + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + casoQuery,
                triage: "{{ route('preview.estudiante.triage') }}" + casoQuery,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + casoQuery,
                abcde: "{{ route('preview.estudiante.abcde') }}" + casoQuery
            };

            const enlaces = {
                sidebarFicha: rutas.admision,
                sidebarAdmision: rutas.admision,
                sidebarTriage: rutas.triage,
                sidebarSignos: rutas.signos,
                sidebarABCDE: rutas.abcde,
                tabResumen: rutas.detalle,
                tabAdmision: rutas.admision,
                tabTriage: rutas.triage,
                tabSignos: rutas.signos,
                tabABCDE: rutas.abcde,
                backToCase: rutas.detalle,
                cancelButton: rutas.detalle
            };

            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });

            // Menú lateral
            $('menuToggle').addEventListener('click', function() {
                const oculto = $('appShell').classList.toggle('sidebar-hidden');
                this.setAttribute('aria-expanded', String(!oculto));
            });

            // Datos de demostración por caso
            function datosIniciales() {
                const base = [{
                        id: 1,
                        fecha: '23/09 05:00',
                        fc: 98,
                        pas: 115,
                        pad: 75,
                        fr: 20,
                        temp: 36.8,
                        spo2: 96,
                        hgt: 100,
                        peso: 68,
                        talla: 162,
                        imc: 25.9,
                        glasgow: 15,
                        observaciones: ''
                    },
                    {
                        id: 2,
                        fecha: '23/09 06:00',
                        fc: 105,
                        pas: 110,
                        pad: 70,
                        fr: 22,
                        temp: 37.1,
                        spo2: 94,
                        hgt: 102,
                        peso: 68,
                        talla: 162,
                        imc: 25.9,
                        glasgow: 15,
                        observaciones: ''
                    },
                    {
                        id: 3,
                        fecha: '23/09 07:00',
                        fc: 115,
                        pas: 100,
                        pad: 65,
                        fr: 26,
                        temp: 36.9,
                        spo2: 91,
                        hgt: 105,
                        peso: 68,
                        talla: 162,
                        imc: 25.9,
                        glasgow: 14,
                        observaciones: ''
                    },
                    {
                        id: 4,
                        fecha: '23/09 08:00',
                        fc: 120,
                        pas: 90,
                        pad: 60,
                        fr: 28,
                        temp: 36.8,
                        spo2: 89,
                        hgt: 110,
                        peso: 68,
                        talla: 162,
                        imc: 25.9,
                        glasgow: 14,
                        observaciones: ''
                    }
                ];
                return casoId === 'CAS-001' ? base : [];
            }

            const storageKey = 'fctp_signos_vitales_v2_' + casoId;
            let registros = datosIniciales();

            try {
                const guardados = sessionStorage.getItem(storageKey);
                if (guardados !== null) {
                    const parsed = JSON.parse(guardados);
                    if (Array.isArray(parsed)) registros = parsed;
                }
            } catch (error) {
                console.warn('No fue posible recuperar los registros temporales.', error);
            }

            let siguienteId = Math.max(0, ...registros.map(r => Number(r.id) || 0)) + 1;

            function persistir() {
                try {
                    sessionStorage.setItem(storageKey, JSON.stringify(registros));
                } catch (error) {
                    console.warn('No fue posible conservar los registros temporalmente.', error);
                }
            }

            // Lectura y validación de números
            function numero(id) {
                const input = $(id);
                if (!input || input.value.trim() === '') return null;
                const valor = Number(input.value);
                return Number.isFinite(valor) ? valor : null;
            }

            function dentro(id, min, max) {
                const valor = numero(id);
                return valor !== null && valor >= min && valor <= max;
            }

            function fechaActual() {
                const fecha = new Date();
                const parte = n => String(n).padStart(2, '0');
                return parte(fecha.getDate()) + '/' + parte(fecha.getMonth() + 1) + ' ' +
                    parte(fecha.getHours()) + ':' + parte(fecha.getMinutes());
            }

            function calcularIMC(peso, talla) {
                if (peso === null || talla === null || peso <= 0 || talla <= 0) return null;
                return Number((peso / Math.pow(talla / 100, 2)).toFixed(1));
            }

            function mostrarMensaje(texto, tipo = 'success') {
                message.textContent = texto;
                message.style.color = tipo === 'error' ? '#b91c1c' : '#166534';
            }

            // Clasificación de presión arterial
            function clasificarPresion(pas, pad) {
                if (pas >= 180 || pad >= 110) return ['critical', 'Crisis hipertensiva', 'Crisis hipertensiva'];
                if (pas >= 140 || pad >= 90) return ['critical', 'Hipertensión', 'HTA etapa I / II'];
                if (pas < 90 || pad < 60) return ['critical', 'Hipotensión severa', 'Hipotensión severa'];
                if (pas >= 121 || pad >= 81) return ['warning', 'Normal alta', 'Normal alta'];
                return ['normal', 'Normotensión', 'Presión arterial normal'];
            }

            // Clasificación de frecuencia cardíaca
            function clasificarFC(v) {
                if (v <= 50) return ['critical', 'Bradicardia', 'Bradicardia crítica'];
                if (v < 60) return ['warning', 'Bradicardia', 'Bradicardia'];
                if (v <= 100) return ['normal', 'Normocardia', 'Frecuencia cardíaca normal'];
                if (v <= 120) return ['warning', 'Taquicardia', 'Taquicardia'];
                return ['critical', 'Taquicardia', 'Taquicardia crítica'];
            }

            // Clasificación de frecuencia respiratoria
            function clasificarFR(v) {
                if (v < 10) return ['critical', 'Bradipnea', 'Bradipnea crítica'];
                if (v < 12) return ['warning', 'Bradipnea', 'Bradipnea'];
                if (v <= 20) return ['normal', 'Eupnea', 'Frecuencia respiratoria normal'];
                if (v <= 28) return ['warning', 'Taquipnea', 'Taquipnea'];
                return ['critical', 'Taquipnea', 'Taquipnea crítica'];
            }

            // Clasificación de saturación
            function clasificarSaturacion(v) {
                if (v < 86) return ['critical', 'Hipoxia severa', 'Hipoxia severa'];
                if (v < 90) return ['warning', 'Hipoxia', 'Alerta de saturación'];
                if (v < 95) return ['info', 'Hipoxia leve', 'Hipoxia leve'];
                return ['normal', 'Saturación normal', 'Saturación normal'];
            }

            // Clasificación de temperatura
            function clasificarTemperatura(v) {
                if (v < 35.5) return ['critical', 'Hipotermia', 'Hipotermia'];
                if (v < 37.3) return ['normal', 'Afebril', 'Temperatura normal'];
                if (v < 37.8) return ['warning', 'Subfebril', 'Febrícula'];
                return ['critical', 'Fiebre', 'Hipertermia'];
            }

            // Clasificación de hemoglucotest
            function clasificarHGT(v) {
                if (v < 70) return ['critical', 'Hipoglicemia', 'Hipoglicemia'];
                if (v <= 100) return ['normal', 'Normoglicemia', 'Glucosa normal'];
                if (v < 200) return ['warning', 'Hiperglicemia moderada', 'Hiperglicemia moderada'];
                return ['critical', 'Hiperglicemia severa', 'Hiperglicemia severa'];
            }

            // Clasificación de Glasgow
            function clasificarGlasgow(v) {
                if (v <= 8) return ['critical', 'Compromiso severo', 'Glasgow severo'];
                if (v <= 12) return ['warning', 'Compromiso moderado', 'Glasgow moderado'];
                if (v <= 14) return ['info', 'Compromiso leve', 'Glasgow leve'];
                return ['normal', 'Glasgow 15', 'Puntaje máximo'];
            }

            // Clasificación de IMC para demostración en adultos
            function clasificarIMC(v) {
                if (v < 18.5) return ['warning', 'Bajo peso', 'Bajo peso'];
                if (v < 25) return ['normal', 'Normal', 'Peso normal'];
                if (v < 30) return ['warning', 'Sobrepeso', 'Sobrepeso'];
                return ['critical', 'Obesidad', 'Obesidad'];
            }

            // Mostrar cuadros de clasificación
            function mostrarClasificacion(id, resultado) {
                const elemento = $(id);
                if (!elemento) return;

                elemento.replaceChildren();
                elemento.className = 'threshold-alert';

                if (!resultado) return;

                const [estado, fisiologico, clinico] = resultado;
                elemento.classList.add('alert-' + estado);

                const fila1 = document.createElement('div');
                fila1.className = 'threshold-row';
                const titulo1 = document.createElement('strong');
                titulo1.textContent = 'Rango fisiológico: ';
                fila1.append(titulo1, document.createTextNode(fisiologico));

                const fila2 = document.createElement('div');
                fila2.className = 'threshold-row';
                const titulo2 = document.createElement('strong');
                titulo2.textContent = 'Clasificación clínica: ';
                fila2.append(titulo2, document.createTextNode(clinico));

                elemento.append(fila1, fila2);
            }

            // Mostrar advertencias de valores atípicos
            function mostrarAdvertencia(id, texto) {
                const elemento = $(id);
                if (!elemento) return;

                elemento.replaceChildren();
                elemento.className = 'atypical-alert';

                if (!texto) return;

                elemento.classList.add('invalid-warning');

                const titulo = document.createElement('strong');
                titulo.textContent = '⚠ Valor atípico detectado';

                const descripcion = document.createElement('span');
                descripcion.textContent = texto;

                const nota = document.createElement('small');
                nota.textContent = 'Verifica el valor antes de continuar.';

                elemento.append(titulo, descripcion, nota);
            }

            // Reglas de valores de referencia
            const reglas = [{
                    id: 'frecuenciaCardiaca',
                    alerta: 'alertFrecuenciaCardiaca',
                    aviso: 'atypicalFrecuenciaCardiaca',
                    min: 20,
                    max: 250,
                    unidad: 'lpm',
                    clasificar: clasificarFC
                },
                {
                    id: 'frecuenciaRespiratoria',
                    alerta: 'alertFrecuenciaRespiratoria',
                    aviso: 'atypicalFrecuenciaRespiratoria',
                    min: 5,
                    max: 60,
                    unidad: 'rpm',
                    clasificar: clasificarFR
                },
                {
                    id: 'saturacion',
                    alerta: 'alertSaturacion',
                    aviso: 'atypicalSaturacion',
                    min: 50,
                    max: 100,
                    unidad: '%',
                    clasificar: clasificarSaturacion
                },
                {
                    id: 'temperatura',
                    alerta: 'alertTemperatura',
                    aviso: 'atypicalTemperatura',
                    min: 30,
                    max: 43,
                    unidad: '°C',
                    clasificar: clasificarTemperatura
                },
                {
                    id: 'hemoglucotest',
                    alerta: 'alertHemoglucotest',
                    aviso: 'atypicalHemoglucotest',
                    min: 20,
                    max: 600,
                    unidad: 'mg/dL',
                    clasificar: clasificarHGT
                },
                {
                    id: 'glasgow',
                    alerta: 'alertGlasgow',
                    aviso: 'atypicalGlasgow',
                    min: 3,
                    max: 15,
                    unidad: 'puntos',
                    clasificar: clasificarGlasgow
                }
            ];

            // Actualizar rangos y alertas
            function actualizarRangos() {
                reglas.forEach(regla => {
                    const input = $(regla.id);
                    const valor = numero(regla.id);
                    const valido = valor !== null && valor >= regla.min && valor <= regla.max && input
                        .checkValidity();

                    mostrarClasificacion(regla.alerta, valido ? regla.clasificar(valor) : null);
                    mostrarAdvertencia(regla.aviso, valor !== null && !valido ?
                        'Valor fuera del rango de referencia (' + regla.min + ' a ' + regla.max + ' ' +
                        regla.unidad + ').' : '');

                    input.classList.toggle('atypical-input', valor !== null && !valido);
                });

                const pas = numero('presionSistolica');
                const pad = numero('presionDiastolica');
                const presionCompleta = pas !== null && pad !== null;
                const presionValida = presionCompleta && pas >= 1 && pas <= 300 && pad >= 1 && pad <= 200 && pas >
                    pad;

                mostrarClasificacion('alertPresion', presionValida ? clasificarPresion(pas, pad) : null);

                let avisoPresion = '';
                if (presionCompleta && pas <= pad) avisoPresion =
                    'La presión sistólica debe superar a la diastólica.';
                else if (presionCompleta && !presionValida) avisoPresion =
                    'Verifica los valores de presión arterial ingresados.';

                mostrarAdvertencia('atypicalPresion', avisoPresion);
                $('presionSistolica').classList.toggle('atypical-input', avisoPresion !== '');
                $('presionDiastolica').classList.toggle('atypical-input', avisoPresion !== '');

                [
                    ['peso', 'atypicalPeso', 1, 350, 'kg'],
                    ['talla', 'atypicalTalla', 30, 250, 'cm']
                ].forEach(regla => {
                    const [id, alerta, min, max, unidad] = regla;
                    const valor = numero(id);
                    const invalido = valor !== null && (valor < min || valor > max);

                    mostrarAdvertencia(alerta, invalido ? 'Valor fuera del rango de referencia (' + min +
                        ' a ' + max + ' ' + unidad + ').' : '');
                    $(id).classList.toggle('atypical-input', invalido);
                });
            }

            // Calcular IMC automáticamente
            function actualizarIMC() {
                const peso = numero('peso');
                const talla = numero('talla');

                $('imc').value = '';
                mostrarClasificacion('alertIMC', null);

                if (!dentro('peso', 1, 350) || !dentro('talla', 30, 250)) return;

                const resultado = calcularIMC(peso, talla);
                if (resultado === null) return;

                $('imc').value = resultado.toFixed(1);
                mostrarClasificacion('alertIMC', clasificarIMC(resultado));
            }

            // Actualizar indicadores al escribir
            function actualizarIndicadores() {
                actualizarRangos();
                actualizarIMC();
            }

            const camposNumericos = [
                'presionSistolica', 'presionDiastolica', 'frecuenciaCardiaca',
                'frecuenciaRespiratoria', 'saturacion', 'temperatura',
                'hemoglucotest', 'glasgow', 'peso', 'talla'
            ];

            camposNumericos.forEach(id => {
                $(id).addEventListener('input', actualizarIndicadores);
                $(id).addEventListener('change', actualizarIndicadores);
            });

            // Renderizar historial
            function renderHistorial() {
                tbody.replaceChildren();

                if (registros.length === 0) {
                    const fila = document.createElement('tr');
                    const celda = document.createElement('td');
                    celda.colSpan = 10;
                    celda.textContent = 'No hay registros disponibles para este caso.';
                    fila.appendChild(celda);
                    tbody.appendChild(fila);
                    return;
                }

                [...registros].reverse().forEach(registro => {
                    const fila = document.createElement('tr');
                    const valores = [
                        registro.fecha,
                        registro.fc,
                        registro.pas + '/' + registro.pad,
                        registro.fr,
                        registro.temp,
                        registro.spo2,
                        registro.hgt,
                        registro.peso,
                        registro.imc
                    ];

                    valores.forEach(valor => {
                        const celda = document.createElement('td');
                        celda.textContent = valor === null || valor === undefined ? '—' : String(
                            valor);
                        fila.appendChild(celda);
                    });

                    const acciones = document.createElement('td');
                    const editar = document.createElement('button');
                    editar.type = 'button';
                    editar.className = 'vitals-edit';
                    editar.textContent = 'Editar';
                    editar.addEventListener('click', () => editarRegistro(registro.id));

                    acciones.appendChild(editar);
                    fila.appendChild(acciones);
                    tbody.appendChild(fila);
                });
            }

            // Crear gráfico de evolución
            function crearGrafico() {
                if (typeof Chart === 'undefined') {
                    console.warn('Chart.js no está disponible.');
                    return;
                }


                const series = [
                    ['FC', 'fc', '#2563eb'],
                    ['PA sistólica', 'pas', '#0d9488'],
                    ['PA diastólica', 'pad', '#a855f7'],
                    ['FR', 'fr', '#111827'],
                    ['SatO₂', 'spo2', '#0891b2'],
                    ['Temperatura', 'temp', '#dc2626']
                ];


                grafico = new Chart($('graficoSignos'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: series.map(([nombre, clave, color]) => ({
                            label: nombre,
                            data: [],
                            borderColor: color,
                            backgroundColor: color,
                            tension: 0.25,
                            borderWidth: 2,
                            pointRadius: 3,
                            spanGaps: false,
                            clave: clave
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                type: 'linear',
                                min: 0,
                                beginAtZero: true,
                                grid: {
                                    color: '#edf2f7'
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                actualizarGrafico();
            }

            // Crear gráfico independiente de Glasgow
            function crearGraficoGlasgow() {
                if (typeof Chart === 'undefined') return;

                graficoGlasgow = new Chart($('graficoGlasgow'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Glasgow',
                            data: [],
                            borderColor: '#6d28d9',
                            backgroundColor: 'rgba(109, 40, 217, 0.10)',
                            borderWidth: 2.5,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#6d28d9',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            tension: 0.2,
                            fill: true,
                            spanGaps: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: context => 'Glasgow: ' + context.parsed.y + ' / 15 puntos'
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                type: 'linear',
                                min: 3,
                                max: 15,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                grid: {
                                    color: '#edf2f7'
                                }
                            }
                        }
                    }
                });

                actualizarGraficoGlasgow();
            }

            // Actualizar gráfico independiente de Glasgow
            function actualizarGraficoGlasgow() {
                if (!graficoGlasgow) return;
                graficoGlasgow.data.labels = registros.map(r => r.fecha);
                graficoGlasgow.data.datasets[0].data = registros.map(r => r.glasgow ?? null);
                graficoGlasgow.update();
            }

            // Actualizar gráfico con el historial
            function actualizarGrafico() {
                if (!grafico) return;

                grafico.data.labels = registros.map(r => r.fecha);
                grafico.data.datasets.forEach(dataset => {
                    dataset.data = registros.map(r => r[dataset.clave] ?? null);
                });
                grafico.update();
            }

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                actualizarIndicadores();

                if (!form.reportValidity()) {
                    mostrarMensaje('Completa los campos obligatorios y revisa los valores ingresados.', 'error');
                    return;
                }

                const pas = numero('presionSistolica');
                const pad = numero('presionDiastolica');

                if (pas === null || pad === null || pas <= pad || pas > 300 || pad > 200 || pas < 1 || pad < 1) {
                    mostrarMensaje('Verifica la presión arterial: la sistólica debe superar a la diastólica.', 'error');
                    return;
                }

                const valoresValidos = [
                    ['frecuenciaCardiaca', 20, 250],
                    ['frecuenciaRespiratoria', 5, 60],
                    ['saturacion', 50, 100],
                    ['temperatura', 30, 43],
                    ['hemoglucotest', 20, 600],
                    ['glasgow', 3, 15]
                ];

                if (valoresValidos.some(([id, min, max]) => !dentro(id, min, max))) {
                    mostrarMensaje('Hay valores fuera de los rangos permitidos. Revisa las advertencias.', 'error');
                    return;
                }

                if ((numero('peso') !== null && !dentro('peso', 1, 350)) ||
                    (numero('talla') !== null && !dentro('talla', 30, 250))) {
                    mostrarMensaje('Revisa los valores de peso y talla.', 'error');
                    return;
                }

                const peso = numero('peso');
                const talla = numero('talla');

                const registro = {
                    id: registroEditando ?? siguienteId,
                    fecha: fechaActual(),
                    fc: numero('frecuenciaCardiaca'),
                    pas: pas,
                    pad: pad,
                    fr: numero('frecuenciaRespiratoria'),
                    temp: numero('temperatura'),
                    spo2: numero('saturacion'),
                    hgt: numero('hemoglucotest'),
                    peso: peso,
                    talla: talla,
                    imc: calcularIMC(peso, talla),
                    glasgow: numero('glasgow'),
                    observaciones: form.querySelector('[name="observaciones"]').value.trim()
                };

                message.textContent = 'Guardando en la base de datos...';

                try {
                    const token = localStorage.getItem('auth_token');
                    
                    const payload = {
                        frecuencia_cardiaca: registro.fc,
                        presion_arterial: registro.pas + '/' + registro.pad,
                        frecuencia_respiratoria: registro.fr,
                        temperatura: registro.temp,
                        saturacion_oxigeno: registro.spo2,
                        glicemia_hgt: registro.hgt,
                        peso: registro.peso,
                        talla: registro.talla ? registro.talla / 100 : null,
                        observaciones: registro.observaciones,
                    };

                    const response = await fetch('/api/fichas/1/signos-vitales', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json();

                    if (response.status === 201 || response.status === 200) {
                        mostrarMensaje('Signos vitales guardados en la base de datos. ✅');
                    } else {
                        console.error('Error del servidor:', data);
                        mostrarMensaje('Error al guardar en la base de datos.', 'error');
                    }
                } catch (error) {
                    console.error('Error de red:', error);
                    mostrarMensaje('Error de conexión. Se guardó localmente.', 'error');
                }

                if (registroEditando !== null) {
                    const indice = registros.findIndex(r => r.id === registroEditando);
                    if (indice < 0) {
                        mostrarMensaje('No se encontró el registro que deseas editar.', 'error');
                        return;
                    }
                    registro.fecha = registros[indice].fecha;
                    registros[indice] = registro;
                    mostrarMensaje('Registro actualizado correctamente.');
                } else {
                    registros.push(registro);
                    siguienteId++;
                    mostrarMensaje('Registro agregado al historial correctamente.');
                }

                registroEditando = null;
                $('guardarRegistro').textContent = 'Guardar registro';
                persistir();
                renderHistorial();
                actualizarGrafico();
                actualizarGraficoGlasgow();
                actualizarIndicadores();
            });

            // Editar registro existente
            function editarRegistro(id) {
                const registro = registros.find(r => r.id === id);
                if (!registro) return;

                registroEditando = id;

                const valores = {
                    frecuenciaCardiaca: registro.fc,
                    presionSistolica: registro.pas,
                    presionDiastolica: registro.pad,
                    frecuenciaRespiratoria: registro.fr,
                    temperatura: registro.temp,
                    saturacion: registro.spo2,
                    hemoglucotest: registro.hgt,
                    glasgow: registro.glasgow,
                    peso: registro.peso,
                    talla: registro.talla
                };

                Object.entries(valores).forEach(([idCampo, valor]) => {
                    $(idCampo).value = valor ?? '';
                });

                form.querySelector('[name="observaciones"]').value = registro.observaciones || '';
                $('guardarRegistro').textContent = 'Actualizar registro';
                actualizarIndicadores();
                mostrarMensaje('Editando registro del ' + registro.fecha);
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            // Preparar nuevo registro
            function nuevoRegistro() {
                registroEditando = null;
                form.reset();
                $('guardarRegistro').textContent = 'Guardar registro';
                actualizarIndicadores();
                mostrarMensaje('Nuevo registro listo para ingresar.');
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                $('frecuenciaCardiaca').focus();
            }

            $('nuevoRegistro').addEventListener('click', nuevoRegistro);

            // Modal para limpiar formulario
            const modal = $('clearModal');

            function cerrarModal() {
                modal.hidden = true;
                document.body.style.overflow = '';
            }

            $('clearButton').addEventListener('click', function() {
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
                $('cancelClear').focus();
            });

            $('cancelClear').addEventListener('click', cerrarModal);

            $('confirmClear').addEventListener('click', function() {
                registroEditando = null;
                form.reset();
                $('guardarRegistro').textContent = 'Guardar registro';
                actualizarIndicadores();
                mostrarMensaje('Formulario limpiado.');
                cerrarModal();
            });

            modal.addEventListener('click', function(event) {
                if (event.target === modal) cerrarModal();
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.hidden) cerrarModal();
            });

            // Inicialización
            renderHistorial();
            crearGrafico();
            crearGraficoGlasgow();
            actualizarIndicadores();
        });
    </script>
</body>

</html>