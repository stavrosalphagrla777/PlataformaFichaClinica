
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Triage | Ficha Clínica TP</title>

    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
</head>

<body>
    <div class="app app-shell" id="appShell">

        <!-- MENÚ LATERAL -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <img
                        src="{{ asset('images/logo-tp.png') }}"
                        alt="Logo TP"
                    >
                </div>

                <span>Ficha Clínica TP</span>
            </div>

            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div>

                <a
                    class="nav-link"
                    href="{{ url('/estudiante/casos-preview') }}"
                >
                    Mis casos clínicos
                </a>

                <div class="nav-title">Atención clínica</div>

                <a class="nav-link" id="sidebarFicha" href="#">
                    Ficha clínica
                </a>

                <a class="nav-link" id="sidebarAdmision" href="#">
                    Admisión
                </a>

                <a
                    class="nav-link active"
                    href="#"
                    aria-current="page"
                >
                    Triage
                </a>

                <a class="nav-link nav-disabled" href="#">
                    Signos vitales
                </a>

                <a class="nav-link nav-disabled" href="#">
                    Evaluación clínica
                </a>

                <div class="nav-title">Cuenta</div>

                <a class="nav-link nav-disabled" href="#">
                    Mi perfil
                </a>

                <a class="nav-link nav-disabled" href="#">
                    Cerrar sesión
                </a>
            </nav>
        </aside>

        <!-- ÁREA PRINCIPAL -->
        <div class="main-area">

            <!-- BARRA SUPERIOR -->
            <header class="topbar">
                <div class="topbar-left">
                    <button
                        type="button"
                        class="menu-toggle"
                        id="menuToggle"
                        aria-label="Mostrar u ocultar menú"
                        aria-expanded="true"
                        aria-controls="sidebar"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="topbar-title">Triage</span>
                </div>

                <div class="user-info">
                    <div class="user-details">
                        <strong>Estudiante</strong>
                        <small>4.º medio</small>
                    </div>

                    <div class="user-avatar">E</div>
                </div>
            </header>

            <!-- CONTENIDO -->
            <main class="content">

                <!-- ENCABEZADO -->
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA</p>

                        <h1>Evaluación de Triage</h1>

                        <p class="muted">
                            Registra la evaluación inicial y asigna la prioridad del caso.
                        </p>
                    </div>

                    <a
                        class="btn btn-secondary"
                        id="backToCase"
                        href="{{ url('/estudiante/casos-preview') }}"
                    >
                        ← Volver al caso
                    </a>
                </div>

                <!-- INFORMACIÓN DEL CASO -->
                <div class="case-banner">
                    <div>
                        <span class="case-code" id="caseCode">
                            CAS-001
                        </span>

                        <strong id="caseTitle">
                            Alteración neurológica aguda
                        </strong>

                        <small>
                            Paciente simulado · Evaluación inicial
                        </small>
                    </div>

                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- NAVEGACIÓN ENTRE ETAPAS -->
                <nav class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a id="tabAdmision" href="#">Admisión</a>

                    <a
                        class="selected"
                        href="#"
                        aria-current="page"
                    >
                        Triage
                    </a>

                    <span>Signos vitales</span>
                    <span>ABCDE</span>
                    <span>Anamnesis</span>
                    <span>Examen físico</span>
                    <span>Procedimientos</span>
                    <span>Medicamentos</span>
                    <span>Exámenes</span>
                    <span>Acciones de enfermería</span>
                    <span>Destino</span>
                </nav>

                <!-- FORMULARIO -->
                <form id="triageForm" class="form-card" novalidate>

                    <!-- MOTIVO DE CONSULTA -->
                    <section class="form-section">
                        <h2>1. Motivo de consulta <em>*</em></h2>

                        <label class="triage-field">
                            <span>Motivo de consulta</span>

                            <textarea
                                name="motivo"
                                rows="4"
                                required
                                placeholder="Describe el motivo de consulta del paciente"
                            ></textarea>
                        </label>
                    </section>

                    <!-- NIVEL ESI -->
                    <section class="form-section">
                        <h2>2. Nivel de prioridad (ESI) <em>*</em></h2>

                        <div
                            class="esi-options"
                            role="radiogroup"
                            aria-label="Nivel de prioridad ESI"
                        >
                            <label class="esi-option esi-1">
                                <input
                                    type="radio"
                                    name="esi"
                                    value="1"
                                    required
                                >

                                <strong>ESI 1</strong>
                                <small>Reanimación</small>
                            </label>

                            <label class="esi-option esi-2">
                                <input
                                    type="radio"
                                    name="esi"
                                    value="2"
                                >

                                <strong>ESI 2</strong>
                                <small>Emergente</small>
                            </label>

                            <label class="esi-option esi-3">
                                <input
                                    type="radio"
                                    name="esi"
                                    value="3"
                                >

                                <strong>ESI 3</strong>
                                <small>Urgente</small>
                            </label>

                            <label class="esi-option esi-4">
                                <input
                                    type="radio"
                                    name="esi"
                                    value="4"
                                >

                                <strong>ESI 4</strong>
                                <small>Menos urgente</small>
                            </label>

                            <label class="esi-option esi-5">
                                <input
                                    type="radio"
                                    name="esi"
                                    value="5"
                                >

                                <strong>ESI 5</strong>
                                <small>No urgente</small>
                            </label>
                        </div>
                    </section>

                    <!-- ESCALA EVA -->
                    <section class="form-section">
                        <h2>3. Escala de dolor (EVA)</h2>

                        <div class="eva-row">
                            <label for="eva">
                                Intensidad del dolor (0 a 10)
                            </label>

                            <output id="evaValue" for="eva">
                                Sin registrar
                            </output>
                        </div>

                        <input
                            type="range"
                            name="eva"
                            id="eva"
                            min="0"
                            max="10"
                            step="1"
                            value="0"
                            disabled
                        >

                        <div class="eva-scale">
                            <span>0 · Sin dolor</span>
                            <span>10 · Máximo dolor</span>
                        </div>

                        <label class="eva-enable">
                            <input
                                type="checkbox"
                                id="evaEnabled"
                            >

                            Registrar escala EVA
                        </label>
                    </section>

                    <!-- SIGNOS VITALES -->
                    <section class="form-section">
                        <h2>4. Signos vitales iniciales</h2>

                        <p class="triage-help">
                            Los valores extraordinarios generan una advertencia
                            de verificación, pero no impiden guardar el registro.
                        </p>

                        <div class="form-grid cols-3 triage-grid">

                            <!-- PRESIÓN ARTERIAL -->
                            <div class="vital-field">
                                <label>
                                    Presión arterial (mmHg) <em>*</em>
                                </label>

                                <div class="blood-pressure">
                                    <input
                                        type="number"
                                        name="presion_sistolica"
                                        id="presionSistolica"
                                        min="0"
                                        step="1"
                                        required
                                        placeholder="Sistólica 1 a 300"
                                        aria-label="Presión sistólica"
                                    >

                                    <span class="pressure-divider">/</span>

                                    <input
                                        type="number"
                                        name="presion_diastolica"
                                        id="presionDiastolica"
                                        min="0"
                                        step="1"
                                        required
                                        placeholder="Diastólica 1 a 200"
                                        aria-label="Presión diastólica"
                                    >
                                </div>

                                <div
                                    class="threshold-alert"
                                    id="alertPresion"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalPresion"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- FRECUENCIA CARDÍACA -->
                            <div class="vital-field">
                                <label for="frecuenciaCardiaca">
                                    Frecuencia cardíaca (lpm) <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="frecuencia_cardiaca"
                                    id="frecuenciaCardiaca"
                                    min="0"
                                    step="1"
                                    required
                                    placeholder="20 a 250"
                                >

                                <div
                                    class="threshold-alert"
                                    id="alertFrecuenciaCardiaca"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalFrecuenciaCardiaca"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- FRECUENCIA RESPIRATORIA -->
                            <div class="vital-field">
                                <label for="frecuenciaRespiratoria">
                                    Frecuencia respiratoria (rpm) <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="frecuencia_respiratoria"
                                    id="frecuenciaRespiratoria"
                                    min="0"
                                    step="1"
                                    required
                                    placeholder="5 a 60"
                                >

                                <div
                                    class="threshold-alert"
                                    id="alertFrecuenciaRespiratoria"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalFrecuenciaRespiratoria"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- SATURACIÓN -->
                            <div class="vital-field">
                                <label for="saturacion">
                                    Saturación de O₂ (%) <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="saturacion"
                                    id="saturacion"
                                    min="0"
                                    max="100"
                                    step="1"
                                    required
                                    placeholder="50 a 100"
                                >

                                <div
                                    class="threshold-alert"
                                    id="alertSaturacion"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalSaturacion"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- TEMPERATURA -->
                            <div class="vital-field">
                                <label for="temperatura">
                                    Temperatura axilar (°C) <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="temperatura"
                                    id="temperatura"
                                    min="0"
                                    step="0.1"
                                    required
                                    placeholder="30.0 a 43.0"
                                >

                                <div
                                    class="threshold-alert"
                                    id="alertTemperatura"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalTemperatura"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- HEMOGLUCOTEST -->
                            <div class="vital-field">
                                <label for="hemoglucotest">
                                    Hemoglucotest (mg/dL) <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="hemoglucotest"
                                    id="hemoglucotest"
                                    min="0"
                                    step="1"
                                    required
                                    placeholder="20 a 600"
                                >

                                <div
                                    class="threshold-alert"
                                    id="alertHemoglucotest"
                                    aria-live="polite"
                                ></div>

                                <div
                                    class="atypical-alert"
                                    id="atypicalHemoglucotest"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- GLASGOW -->
                            <div class="vital-field">
                                <label for="glasgow">
                                    Glasgow <em>*</em>
                                </label>

                                <input
                                    type="number"
                                    name="glasgow"
                                    id="glasgow"
                                    min="3"
                                    max="15"
                                    step="1"
                                    required
                                    placeholder="3 a 15"
                                >

                                <div
                                    class="atypical-alert"
                                    id="atypicalGlasgow"
                                    aria-live="polite"
                                ></div>
                            </div>
                        </div>
                    </section>

                    <!-- ANTROPOMETRÍA -->
                    <section class="form-section">
                        <h2>5. Antropometría</h2>

                        <p class="triage-help">
                            Peso y talla son opcionales en esta etapa.
                            No se calcula IMC en Triage.
                        </p>

                        <div class="form-grid cols-3 triage-grid">

                            <!-- PESO -->
                            <div class="vital-field">
                                <label for="peso">
                                    Peso (kg)
                                    <span class="optional-label">Opcional</span>
                                </label>

                                <input
                                    name="peso"
                                    id="peso"
                                    type="number"
                                    min="0"
                                    step="any"
                                    placeholder="Opcional: 1 a 350"
                                >

                                <div
                                    class="atypical-alert"
                                    id="atypicalPeso"
                                    aria-live="polite"
                                ></div>
                            </div>

                            <!-- TALLA -->
                            <div class="vital-field">
                                <label for="talla">
                                    Talla (cm)
                                    <span class="optional-label">Opcional</span>
                                </label>

                                <input
                                    name="talla"
                                    id="talla"
                                    type="number"
                                    min="0"
                                    step="any"
                                    placeholder="Opcional: 30 a 250"
                                >

                                <div
                                    class="atypical-alert"
                                    id="atypicalTalla"
                                    aria-live="polite"
                                ></div>
                            </div>
                        </div>
                    </section>

                    <!-- PRIORIDAD ASIGNADA -->
                    <section class="form-section">
                        <h2>6. Prioridad asignada</h2>

                        <div
                            class="priority-summary"
                            id="prioritySummary"
                            aria-live="polite"
                        >
                            <strong id="priorityLabel">
                                Sin clasificar
                            </strong>

                            <span id="priorityDescription">
                                Selecciona un nivel ESI.
                            </span>
                        </div>
                    </section>

                    <!-- OBSERVACIONES -->
                    <section class="form-section">
                        <h2>7. Observaciones del Triage</h2>

                        <label class="triage-field">
                            <span>Observaciones</span>

                            <textarea
                                name="observaciones"
                                rows="4"
                                placeholder="Registra observaciones relevantes"
                            ></textarea>
                        </label>
                    </section>

                    <!-- ACCIONES -->
                    <div class="form-actions">
                        <p
                            class="save-message"
                            id="saveMessage"
                            role="status"
                            aria-live="polite"
                        ></p>

                        <button
                            type="button"
                            class="btn btn-light"
                            id="clearButton"
                        >
                            Limpiar
                        </button>

                        <a
                            class="btn btn-light"
                            id="cancelButton"
                            href="{{ url('/estudiante/casos-preview') }}"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Guardar Triage
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
    <div class="modal-overlay" id="clearModal" hidden>
        <div
            class="modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="clearModalTitle"
            aria-describedby="clearModalDescription"
        >
            <div class="modal-icon">!</div>

            <h2 id="clearModalTitle">
                Limpiar formulario
            </h2>

            <p id="clearModalDescription">
                ¿Estás seguro de que deseas limpiar los datos
                ingresados en Triage? Se descartarán los cambios
                no guardados.
            </p>

            <div class="modal-actions">
                <button
                    type="button"
                    class="modal-cancel"
                    id="cancelClear"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="modal-confirm"
                    id="confirmClear"
                >
                    Sí, limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        (function () {
            'use strict';

            // =====================================================
            // MENÚ LATERAL
            // =====================================================

            const shell = document.getElementById('appShell');
            const toggle = document.getElementById('menuToggle');

            toggle.addEventListener('click', function () {
                const hidden = shell.classList.toggle('sidebar-hidden');

                toggle.setAttribute(
                    'aria-expanded',
                    String(!hidden)
                );
            });

            // =====================================================
            // IDENTIFICACIÓN DEL CASO
            // =====================================================

            const params = new URLSearchParams(window.location.search);

            const caseId = /^CAS-00[1-5]$/.test(params.get('caso') || '')
                ? params.get('caso')
                : 'CAS-001';

            const titles = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };

            document.getElementById('caseCode').textContent = caseId;
            document.getElementById('caseTitle').textContent = titles[caseId];

            const storageKey = 'ficha-tp-triage-demo-' + caseId;

            // =====================================================
            // NAVEGACIÓN ENTRE VISTAS
            // =====================================================

            const admissionUrl =
                @json(route('preview.estudiante.admision')) +
                '?caso=' +
                encodeURIComponent(caseId);

            const detailUrl =
                @json(url('/estudiante/caso-preview')) +
                '/' +
                encodeURIComponent(caseId);

            document.getElementById('tabAdmision').href = admissionUrl;
            document.getElementById('sidebarAdmision').href = admissionUrl;
            document.getElementById('sidebarFicha').href = admissionUrl;

            document.getElementById('tabResumen').href = detailUrl;
            document.getElementById('backToCase').href = detailUrl;
            document.getElementById('cancelButton').href = detailUrl;

            // =====================================================
            // ELEMENTOS DEL FORMULARIO
            // =====================================================

            const form = document.getElementById('triageForm');
            const message = document.getElementById('saveMessage');

            // =====================================================
            // CLASIFICACIÓN ESI
            // =====================================================

            const esiInputs = Array.from(
                form.querySelectorAll('input[name="esi"]')
            );

            const prioritySummary = document.getElementById('prioritySummary');
            const priorityLabel = document.getElementById('priorityLabel');
            const priorityDescription = document.getElementById('priorityDescription');

            const esiNames = {
                1: 'Reanimación',
                2: 'Emergente',
                3: 'Urgente',
                4: 'Menos urgente',
                5: 'No urgente'
            };

            function updatePriority() {
                const selected = esiInputs.find(function (input) {
                    return input.checked;
                });

                prioritySummary.className = 'priority-summary';

                if (!selected) {
                    priorityLabel.textContent = 'Sin clasificar';
                    priorityDescription.textContent = 'Selecciona un nivel ESI.';
                    return;
                }

                prioritySummary.classList.add(
                    'priority-' + selected.value
                );

                priorityLabel.textContent = 'ESI ' + selected.value;
                priorityDescription.textContent = esiNames[selected.value];
            }

            esiInputs.forEach(function (input) {
                input.addEventListener('change', updatePriority);
            });

            // =====================================================
            // ESCALA EVA
            // =====================================================

            const eva = document.getElementById('eva');
            const evaEnabled = document.getElementById('evaEnabled');
            const evaValue = document.getElementById('evaValue');

            function updateEva() {
                eva.disabled = !evaEnabled.checked;

                evaValue.textContent = evaEnabled.checked
                    ? eva.value + ' / 10'
                    : 'Sin registrar';
            }

            evaEnabled.addEventListener('change', updateEva);
            eva.addEventListener('input', updateEva);

            // =====================================================
            // UMBRALES CLÍNICOS
            //
            // Los umbrales determinan las clasificaciones.
            // Los valores atípicos se validan por separado.
            // =====================================================

            const thresholds = [
                {
                    input: 'frecuenciaCardiaca',
                    alert: 'alertFrecuenciaCardiaca',

                    classify: function (v) {
                        if (v <= 50) {
                            return [
                                'critical',
                                'Bradicardia',
                                'Bradicardia crítica'
                            ];
                        }

                        if (v < 60) {
                            return [
                                'warning',
                                'Bradicardia',
                                'Bradicardia'
                            ];
                        }

                        if (v <= 100) {
                            return [
                                'normal',
                                'Normocardia',
                                'Frecuencia cardíaca normal'
                            ];
                        }

                        if (v <= 120) {
                            return [
                                'warning',
                                'Taquicardia',
                                'Taquicardia'
                            ];
                        }

                        return [
                            'critical',
                            'Taquicardia',
                            'Taquicardia crítica'
                        ];
                    }
                },
                {
                    input: 'frecuenciaRespiratoria',
                    alert: 'alertFrecuenciaRespiratoria',

                    classify: function (v) {
                        if (v < 10) {
                            return [
                                'critical',
                                'Bradipnea',
                                'Bradipnea crítica'
                            ];
                        }

                        if (v < 12) {
                            return [
                                'warning',
                                'Bradipnea',
                                'Bradipnea'
                            ];
                        }

                        if (v <= 20) {
                            return [
                                'normal',
                                'Eupnea',
                                'Frecuencia respiratoria normal'
                            ];
                        }

                        if (v <= 28) {
                            return [
                                'warning',
                                'Taquipnea',
                                'Taquipnea'
                            ];
                        }

                        return [
                            'critical',
                            'Taquipnea',
                            'Taquipnea crítica'
                        ];
                    }
                },
                {
                    input: 'saturacion',
                    alert: 'alertSaturacion',

                    classify: function (v) {
                        if (v < 86) {
                            return [
                                'critical',
                                'Hipoxia severa',
                                'Hipoxia severa'
                            ];
                        }

                        if (v < 90) {
                            return [
                                'warning',
                                'Hipoxia',
                                'Alerta de saturación'
                            ];
                        }

                        if (v < 95) {
                            return [
                                'info',
                                'Hipoxia leve',
                                'Hipoxia leve'
                            ];
                        }

                        return [
                            'normal',
                            'Saturación normal',
                            'Saturación normal'
                        ];
                    }
                },
                {
                    input: 'temperatura',
                    alert: 'alertTemperatura',

                    classify: function (v) {
                        if (v < 35.5) {
                            return [
                                'critical',
                                'Hipotermia',
                                'Hipotermia'
                            ];
                        }

                        if (v < 37.3) {
                            return [
                                'normal',
                                'Afebril',
                                'Temperatura normal'
                            ];
                        }

                        if (v < 37.8) {
                            return [
                                'warning',
                                'Subfebril',
                                'Febrícula'
                            ];
                        }

                        return [
                            'critical',
                            'Fiebre',
                            'Hipertermia'
                        ];
                    }
                },
                {
                    input: 'hemoglucotest',
                    alert: 'alertHemoglucotest',

                    classify: function (v) {
                        if (v < 70) {
                            return [
                                'critical',
                                'Hipoglicemia',
                                'Hipoglicemia'
                            ];
                        }

                        if (v <= 100) {
                            return [
                                'normal',
                                'Normoglicemia',
                                'Glucosa normal'
                            ];
                        }

                        if (v < 200) {
                            return [
                                'warning',
                                'Hiperglicemia moderada',
                                'Hiperglicemia moderada'
                            ];
                        }

                        return [
                            'critical',
                            'Hiperglicemia severa',
                            'Hiperglicemia severa'
                        ];
                    }
                }
            ];

            // =====================================================
            // MOSTRAR CLASIFICACIÓN CLÍNICA
            // =====================================================

            function showAlert(element, result) {
                const field = element.closest('.vital-field');

                element.className = 'threshold-alert';
                element.replaceChildren();

                if (field) {
                    field.classList.remove(
                        'status-normal',
                        'status-warning',
                        'status-critical',
                        'status-info'
                    );
                }

                if (!result) {
                    return;
                }

                const [status, physiological, clinical] = result;

                element.classList.add('alert-' + status);

                if (field) {
                    field.classList.add('status-' + status);
                }

                const physiologicalRow = document.createElement('div');
                physiologicalRow.className = 'threshold-row';

                const physiologicalLabel = document.createElement('strong');
                physiologicalLabel.textContent = 'Rango fisiológico: ';

                physiologicalRow.append(
                    physiologicalLabel,
                    document.createTextNode(physiological)
                );

                const clinicalRow = document.createElement('div');
                clinicalRow.className = 'threshold-row';

                const clinicalLabel = document.createElement('strong');
                clinicalLabel.textContent = 'Clasificación clínica: ';

                clinicalRow.append(
                    clinicalLabel,
                    document.createTextNode(clinical)
                );

                element.append(physiologicalRow, clinicalRow);
            }

            // =====================================================
            // CLASIFICACIÓN DE PRESIÓN ARTERIAL
            // =====================================================

            function classifyPressure(pas, pad) {
                if (pas >= 180 || pad >= 110) {
                    return [
                        'critical',
                        'Crisis hipertensiva',
                        'Crisis hipertensiva'
                    ];
                }

                if (pas >= 140 || pad >= 90) {
                    return [
                        'critical',
                        'Hipertensión',
                        'HTA etapa I / II'
                    ];
                }

                if (pas < 90 || pad < 60) {
                    return [
                        'critical',
                        'Hipotensión severa',
                        'Hipotensión severa'
                    ];
                }

                if (pas >= 121 || pad >= 81) {
                    return [
                        'warning',
                        'Normal alta',
                        'Normal alta'
                    ];
                }

                return [
                    'normal',
                    'Normotensión',
                    'Presión arterial normal'
                ];
            }

            // =====================================================
            // ACTUALIZAR CLASIFICACIONES CLÍNICAS
            // =====================================================

            function updateThresholds() {
                thresholds.forEach(function (item) {
                    const input = document.getElementById(item.input);
                    const alert = document.getElementById(item.alert);

                    if (
                        input.value === '' ||
                        !input.checkValidity()
                    ) {
                        showAlert(alert, null);
                        return;
                    }

                    showAlert(
                        alert,
                        item.classify(Number(input.value))
                    );
                });

                const systolic = document.getElementById('presionSistolica');
                const diastolic = document.getElementById('presionDiastolica');
                const pressureAlert = document.getElementById('alertPresion');

                if (
                    systolic.value === '' ||
                    diastolic.value === '' ||
                    !systolic.checkValidity() ||
                    !diastolic.checkValidity()
                ) {
                    showAlert(pressureAlert, null);
                    return;
                }

                showAlert(
                    pressureAlert,
                    classifyPressure(
                        Number(systolic.value),
                        Number(diastolic.value)
                    )
                );
            }

            // =====================================================
            // VALORES ATÍPICOS
            //
            // Rangos preventivos orientativos para la simulación.
            // NO reemplazan los umbrales clínicos del cliente.
            // NO bloquean el guardado.
            // =====================================================

            const atypicalRules = [
                {
                    input: 'frecuenciaCardiaca',
                    alert: 'atypicalFrecuenciaCardiaca',
                    min: 20,
                    max: 250,
                    unit: 'lpm'
                },
                {
                    input: 'frecuenciaRespiratoria',
                    alert: 'atypicalFrecuenciaRespiratoria',
                    min: 5,
                    max: 60,
                    unit: 'rpm'
                },
                {
                    input: 'saturacion',
                    alert: 'atypicalSaturacion',
                    min: 50,
                    max: 100,
                    unit: '%'
                },
                {
                    input: 'temperatura',
                    alert: 'atypicalTemperatura',
                    min: 30,
                    max: 43,
                    unit: '°C'
                },
                {
                    input: 'hemoglucotest',
                    alert: 'atypicalHemoglucotest',
                    min: 20,
                    max: 600,
                    unit: 'mg/dL'
                },
                {
                    input: 'peso',
                    alert: 'atypicalPeso',
                    min: 1,
                    max: 350,
                    unit: 'kg'
                },
                {
                    input: 'talla',
                    alert: 'atypicalTalla',
                    min: 30,
                    max: 250,
                    unit: 'cm'
                }
            ];

            // =====================================================
            // MOSTRAR ADVERTENCIA PREVENTIVA
            // =====================================================

            function showAtypicalWarning(element, text) {
                element.replaceChildren();

                if (!text) {
                    return;
                }

                const title = document.createElement('strong');
                title.textContent = '⚠ Valor atípico detectado';

                const description = document.createElement('span');
                description.textContent = text;

                const note = document.createElement('small');
                note.textContent =
                    'Esta advertencia no impide guardar el registro.';

                element.append(title, description, note);
            }

            // =====================================================
            // VALIDAR VALORES ATÍPICOS
            // =====================================================

            function updateAtypicalWarnings() {
                atypicalRules.forEach(function (rule) {
                    const input = document.getElementById(rule.input);
                    const alert = document.getElementById(rule.alert);

                    input.classList.remove('atypical-input');

                    if (
                        input.value === '' ||
                        !input.checkValidity()
                    ) {
                        showAtypicalWarning(alert, '');
                        return;
                    }

                    const value = Number(input.value);

                    if (value < rule.min || value > rule.max) {
                        const text =
                            'El valor ingresado está fuera del rango ' +
                            'habitual de referencia (' +
                            rule.min + '–' + rule.max + ' ' +
                            rule.unit +
                            '). Verifica que no exista un error de digitación.';

                        showAtypicalWarning(alert, text);

                        input.classList.add('atypical-input');
                    } else {
                        showAtypicalWarning(alert, '');
                    }
                });

                // PRESIÓN ARTERIAL
                const systolic = document.getElementById('presionSistolica');
                const diastolic = document.getElementById('presionDiastolica');
                const pressureAlert = document.getElementById('atypicalPresion');

                systolic.classList.remove('atypical-input');
                diastolic.classList.remove('atypical-input');

                if (
                    systolic.value === '' ||
                    diastolic.value === '' ||
                    !systolic.checkValidity() ||
                    !diastolic.checkValidity()
                ) {
                    showAtypicalWarning(pressureAlert, '');
                    return;
                }

                const pas = Number(systolic.value);
                const pad = Number(diastolic.value);

                const atypicalPressure =
                    pas < 1 ||
                    pas > 300 ||
                    pad < 1 ||
                    pad > 200 ||
                    pad > pas;

                if (atypicalPressure) {
                    let text =
                        'La presión arterial ingresada es poco habitual. ' +
                        'Verifica que los valores sean correctos.';

                    if (pad > pas) {
                        text =
                            'La presión diastólica supera a la sistólica. ' +
                            'Revisa el orden y los valores ingresados.';
                    }

                    showAtypicalWarning(pressureAlert, text);

                    systolic.classList.add('atypical-input');
                    diastolic.classList.add('atypical-input');
                } else {
                    showAtypicalWarning(pressureAlert, '');
                }
            }

            // =====================================================
            // GLASGOW
            //
            // Glasgow tiene una escala definida de 3 a 15.
            // Un valor fuera de ella sí es inválido.
            // =====================================================

            function updateGlasgowWarning() {
                const input = document.getElementById('glasgow');
                const alert = document.getElementById('atypicalGlasgow');

                if (input.value === '') {
                    showAtypicalWarning(alert, '');
                    return;
                }

                const value = Number(input.value);

                if (
                    !Number.isInteger(value) ||
                    value < 3 ||
                    value > 15
                ) {
                    alert.replaceChildren();

                    const title = document.createElement('strong');
                    title.textContent = 'Valor inválido';

                    const description = document.createElement('span');
                    description.textContent =
                        'La escala de Glasgow admite valores enteros ' +
                        'entre 3 y 15. Corrige el dato antes de guardar.';

                    alert.append(title, description);
                    alert.classList.add('invalid-warning');
                } else {
                    alert.classList.remove('invalid-warning');
                    showAtypicalWarning(alert, '');
                }
            }

            // =====================================================
            // ACTUALIZAR TODAS LAS VALIDACIONES
            // =====================================================

            function updateValidations() {
                updateThresholds();
                updateAtypicalWarnings();
                updateGlasgowWarning();
            }

            [
                'frecuenciaCardiaca',
                'frecuenciaRespiratoria',
                'saturacion',
                'temperatura',
                'hemoglucotest',
                'presionSistolica',
                'presionDiastolica',
                'glasgow',
                'peso',
                'talla'
            ].forEach(function (id) {
                document.getElementById(id).addEventListener(
                    'input',
                    updateValidations
                );
            });

            // =====================================================
            // CAPTURAR DATOS
            // =====================================================

            function capture() {
                const data = {};

                Array.from(form.elements).forEach(function (element) {
                    if (!element.name) {
                        return;
                    }

                    if (element.type === 'radio') {
                        if (element.checked) {
                            data[element.name] = element.value;
                        }
                    } else if (element.type !== 'checkbox') {
                        data[element.name] = element.value;
                    }
                });

                data.eva_registrada = evaEnabled.checked;

                if (!evaEnabled.checked) {
                    data.eva = '';
                }

                return data;
            }

            // =====================================================
            // RESTAURAR DATOS
            // =====================================================

            function restore(data) {
                Array.from(form.elements).forEach(function (element) {
                    if (!element.name) {
                        return;
                    }

                    if (element.type === 'radio') {
                        element.checked =
                            data[element.name] === element.value;
                    } else if (element.type !== 'checkbox') {
                        if (
                            Object.prototype.hasOwnProperty.call(
                                data,
                                element.name
                            )
                        ) {
                            element.value = data[element.name];
                        }
                    }
                });

                evaEnabled.checked = !!data.eva_registrada;

                updateEva();
                updatePriority();
                updateValidations();
            }

            // =====================================================
            // MODAL PARA LIMPIAR
            // =====================================================

            const modal = document.getElementById('clearModal');
            const cancelClear = document.getElementById('cancelClear');
            const confirmClear = document.getElementById('confirmClear');

            let previousFocus = null;

            document.getElementById('clearButton').addEventListener(
                'click',
                function () {
                    previousFocus = document.activeElement;

                    modal.hidden = false;
                    document.body.style.overflow = 'hidden';

                    cancelClear.focus();
                }
            );

            function closeModal() {
                modal.hidden = true;
                document.body.style.overflow = '';

                if (previousFocus) {
                    previousFocus.focus();
                }
            }

            cancelClear.addEventListener('click', closeModal);

            confirmClear.addEventListener('click', function () {
                form.reset();
                evaEnabled.checked = false;

                updateEva();
                updatePriority();
                updateValidations();

                message.textContent = 'Formulario limpio.';

                closeModal();
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (modal.hidden) {
                    return;
                }

                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeModal();
                }

                if (event.key === 'Tab') {
                    if (
                        event.shiftKey &&
                        document.activeElement === cancelClear
                    ) {
                        event.preventDefault();
                        confirmClear.focus();
                    } else if (
                        !event.shiftKey &&
                        document.activeElement === confirmClear
                    ) {
                        event.preventDefault();
                        cancelClear.focus();
                    }
                }
            });

            // =====================================================
            // GUARDAR TRIAGE
            //
            // Las advertencias atípicas no bloquean.
            // Solo se bloquean campos obligatorios vacíos
            // y valores inválidos según la escala o formato.
            // =====================================================

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                updateValidations();

                if (!form.reportValidity()) {
                    message.textContent =
                        'Revisa los campos obligatorios o inválidos.';
                    return;
                }

                try {
                    localStorage.setItem(
                        storageKey,
                        JSON.stringify(capture())
                    );

                    message.textContent =
                        'Triage guardado correctamente.';
                } catch (error) {
                    message.textContent =
                        'No se pudo guardar en este navegador.';
                }
            });

            // =====================================================
            // RECUPERAR DATOS
            // =====================================================

            try {
                const saved = localStorage.getItem(storageKey);

                if (saved) {
                    restore(JSON.parse(saved));
                }
            } catch (error) {
                // Si no existen datos válidos, iniciar vacío.
            }

            updateEva();
            updatePriority();
            updateValidations();
        })();
    </script>
</body>
</html>
