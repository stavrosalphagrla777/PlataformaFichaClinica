<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signos vitales y antropometría | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-3ro.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-signos-vitales-3ro.css') }}">
</head>

<body>
    <div class="app app-shell" id="appShell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP"></div><span>Ficha
                    Clínica TP</span>
            </div>
            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div><a class="nav-link"
                    href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
                <div class="nav-title">Atención clínica · 3.º medio</div><a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">Antecedentes</a>
                <a class="nav-link active ces-active"
                    href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">Signos vitales
                    y antropometría</a>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">Examen
                    físico</a>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">Citaciones y
                    próximos controles</a>
                <div class="nav-title">Cuenta</div><form action="{{ route('delivery.logout') }}" method="POST">@csrf<button type="submit" class="nav-link" style="width:100%;background:transparent;border:0;text-align:left;cursor:pointer">Cerrar sesión</button></form>
            </nav>
        </aside>
        <div class="main-area">
            <header class="topbar">
                <div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle"
                        aria-label="Mostrar u ocultar menú" aria-controls="sidebar"
                        aria-expanded="true"><span></span><span></span><span></span></button><span
                        class="topbar-title">Signos vitales y antropometría</span></div>
                <div class="user-info">
                    <div class="user-details"><strong>{{ auth()->user()?->name }}</strong><small>3.º medio · CESFAM</small></div>
                    <div class="user-avatar">E</div>
                </div>
            </header>
            <main class="content">
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA · CESFAM</p>
                        <h1>Signos vitales y antropometría</h1>
                        <p class="muted">Registra mediciones y escribe tu interpretación.</p>
                    </div><a class="btn btn-secondary"
                        href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}">← Volver al caso</a>
                </div>
                <div class="case-banner">
                    <div><span class="case-code">{{ $casoId }}</span><strong>Caso clínico
                            asignado</strong><small>Paciente simulado · 3.º medio</small></div>
                    <div class="ces-case-tools"><span class="case-status">En desarrollo</span></div>
                </div>

                <nav class="tabs ces-tabs" aria-label="Etapas de ficha clínica">

                    <a href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}"
                        aria-label="Ver resumen del caso">
                        Resumen
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">
                        Antecedentes
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}"
                        class="selected" aria-current="page">
                        Signos vitales y antropometría
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">
                        Examen físico
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">
                        Citaciones y próximos controles
                    </a>

                </nav>

                <form class="ces-form" data-ces-form="signos-vitales-3ro" data-caso="{{ $casoId }}">
                    <section class="ces-panel">
                        <h2>Signos vitales</h2>
                        <p class="ces-vital-help">Los rangos se actualizan al ingresar cada valor. Las advertencias son
                            referencias educativas para pacientes adultos simulados.</p>
                        <div class="ces-vital-grid">


                            <div class="ces-presion-field vital-field">
                                <label for="cesPresionSistolica">
                                    Presión arterial (mmHg) <em>*</em>
                                </label>

                                <div class="ces-blood-pressure">
                                    <input type="number" id="cesPresionSistolica" name="pas" min="1"
                                        max="300" step="1" placeholder="Sistólica">

                                    <span class="ces-pressure-divider">/</span>

                                    <input type="number" id="cesPresionDiastolica" name="pad" min="1"
                                        max="200" step="1" placeholder="Diastólica">
                                </div>

                                <div class="ces-threshold-alert" id="cesAlertPresion" aria-live="polite"></div>

                                <div class="ces-atypical-alert" id="cesAtypicalPresion" aria-live="polite"></div>
                            </div>


                            <div class="ces-vital-field"><label for="cesFc">Frecuencia cardíaca (lpm)</label><input
                                    id="cesFc" type="number" name="fc" min="0" step="1"
                                    placeholder="20 a 250">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                            <div class="ces-vital-field"><label for="cesFr">Frecuencia respiratoria
                                    (rpm)</label><input id="cesFr" type="number" name="fr" min="0"
                                    step="1" placeholder="5 a 60">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                            <div class="ces-vital-field"><label for="cesTemperatura">Temperatura (°C)</label><input
                                    id="cesTemperatura" type="number" name="temperatura" min="0"
                                    step="0.1" placeholder="30.0 a 43.0">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                            <div class="ces-vital-field"><label for="cesSpo2">Saturación de oxígeno (%)</label><input
                                    id="cesSpo2" type="number" name="spo2" min="0" max="100"
                                    step="1" placeholder="50 a 100">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                        </div>
                    </section>
                    <section class="ces-panel">
                        <h2>Antropometría</h2>
                        <p class="ces-vital-help">El IMC se calcula automáticamente: peso (kg) / [talla (m)]².</p>
                        <div class="ces-vital-grid">
                            <div class="ces-vital-field"><label for="cesPeso">Peso (kg)</label><input id="cesPeso"
                                    type="number" name="peso" min="0" step="any"
                                    placeholder="1 a 350">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                            <div class="ces-vital-field"><label for="cesTalla">Talla (cm)</label><input
                                    id="cesTalla" type="number" name="talla" min="0" step="any"
                                    placeholder="30 a 250">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                            <div class="ces-vital-field"><label for="cesImc">IMC (calculado)</label><input
                                    id="cesImc" type="text" name="imc" readonly
                                    placeholder="Cálculo automático">
                                <div class="ces-threshold-alert" aria-live="polite"></div>
                                <div class="ces-atypical-alert" aria-live="polite"></div>
                            </div>
                        </div>
                    </section>
                    <section class="ces-panel">
                        <h2>Interpretación del estudiante</h2>
                        <p class="ces-muted">Según lo acordado con el liceo, no se muestra un cuadro de clasificación
                            automática.</p><label>Interpretación de signos vitales y antropometría
                            <textarea name="interpretacion" placeholder="Escribe tu análisis de los valores registrados"></textarea>
                        </label>
                    </section>


                    <!-- BOTONES INFERIORES -->
                    <div class="form-card ces-exam-footer">
                        <div class="form-actions">

                            <p class="save-message" id="cesMessage" role="status" aria-live="polite"></p>

                            <button type="button" class="btn btn-light" id="cesClearButton">
                                Limpiar
                            </button>

                            <a class="btn btn-light" href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">
                                Anterior
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Guardar registro
                            </button>

                        </div>
                    </div>

                </form>


                <!-- MODAL DE CONFIRMACIÓN PARA LIMPIAR -->
                <div class="modal-overlay" id="cesClearModal" hidden>

                    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="cesClearTitle"
                        aria-describedby="cesClearDescription">

                        <div class="modal-icon" aria-hidden="true">
                            !
                        </div>

                        <h2 id="cesClearTitle">
                            Limpiar formulario
                        </h2>

                        <p id="cesClearDescription">
                            Se eliminarán los datos ingresados en este formulario
                            y su borrador guardado en este navegador.
                            ¿Deseas continuar?
                        </p>

                        <div class="modal-actions">

                            <button type="button" class="modal-cancel" id="cesClearCancel">
                                Cancelar
                            </button>

                            <button type="button" class="modal-confirm" id="cesClearConfirm">
                                Sí, limpiar
                            </button>

                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>
    <script>window.fichaThirdYear = {!! json_encode($thirdYearContext, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};</script>
    <script src="{{ asset('js/estudiante/tercero.js') }}" defer></script>
    <script src="{{ asset('js/estudiante/tercero-bridge.js') }}" defer></script>
</body>

</html>
