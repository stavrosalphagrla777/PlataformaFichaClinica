<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Examen físico | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-3ro.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-examen-fisico-3ro.css') }}">
</head>

<body>
    <div class="app app-shell" id="appShell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP"></div><span>Ficha
                    Clínica TP</span>
            </div>
            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div>
                <a class="nav-link" href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
                <div class="nav-title">Atención clínica · 3.º medio</div>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">Antecedentes</a>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">Signos vitales
                    y antropometría</a>
                <a class="nav-link active ces-active"
                    href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}"
                    aria-current="page">Examen físico</a>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">Citaciones y
                    próximos controles</a>
                <div class="nav-title">Cuenta</div>
                @include('shared.notifications-link')
                <form action="{{ route('delivery.logout') }}" method="POST">@csrf<button type="submit" class="nav-link" style="width:100%;background:transparent;border:0;text-align:left;cursor:pointer">Cerrar sesión</button></form>
            </nav>
        </aside>
        <div class="main-area">
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Cerrar menú lateral"
                        aria-controls="sidebar" aria-expanded="true"><span></span><span></span><span></span></button>
                    <span class="topbar-title">Examen físico</span>
                </div>
                <div class="user-info">
                    <div class="user-details"><strong>{{ auth()->user()?->name }}</strong><small>3.º medio · CESFAM</small></div>
                    <div class="user-avatar">E</div>
                </div>
            </header>
            <main class="content">
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA · CESFAM</p>
                        <h1>Examen físico</h1>
                        <p class="muted">Registra el examen general y revisa los hallazgos segmentarios del caso.</p>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}">←
                        Volver al caso</a>
                </div>
                <div class="case-banner">
                    <div><span class="case-code">{{ $casoId }}</span><strong>Caso clínico
                            asignado</strong><small>Paciente simulado · 3.º medio</small></div>
                    <div class="ces-case-tools"><span class="case-status">En desarrollo</span></div>
                </div>
                <nav class="tabs ces-tabs" aria-label="Etapas de ficha clínica">
                    <a href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}">Resumen</a>
                    <a
                        href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">Antecedentes</a>
                    <a href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">Signos
                        vitales y antropometría</a>
                    <a href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}"
                        class="selected" aria-current="page">Examen físico</a>
                    <a href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">Citaciones y
                        próximos controles</a>
                </nav>
                <form class="ces-form ces-exam-form" data-ces-form="examen-fisico-3ro" data-caso="{{ $casoId }}">
                    <div class="ces-exam-layout">
                        <section class="ces-panel ces-exam-card" aria-labelledby="examenGeneralTitulo">
                            <h2 id="examenGeneralTitulo">Examen físico general</h2>
                            <div class="ces-exam-fields">
                                <label>Estado de conciencia<select name="estado_conciencia"
                                        aria-label="Estado conciencia">
                                        <option value="">Seleccionar</option>
                                        <option value="Lúcido, orientado">Lúcido, orientado</option>
                                        <option value="Alerta">Alerta</option>
                                        <option value="Somnoliento">Somnoliento</option>
                                        <option value="Responde a la voz">Responde a la voz</option>
                                        <option value="Responde al dolor">Responde al dolor</option>
                                        <option value="No responde">No responde</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                                <label>Marcha<select name="marcha" aria-label="Marcha">
                                        <option value="">Seleccionar</option>
                                        <option value="Normal, estable">Normal, estable</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Estable con apoyo">Estable con apoyo</option>
                                        <option value="Inestable">Inestable</option>
                                        <option value="No deambula">No deambula</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                                <label>Facie<select name="facie" aria-label="Facie">
                                        <option value="">Seleccionar</option>
                                        <option value="Normotípica">Normotípica</option>
                                        <option value="Sin alteraciones">Sin alteraciones</option>
                                        <option value="Dolorosa">Dolorosa</option>
                                        <option value="Ansiosa">Ansiosa</option>
                                        <option value="Pálida">Pálida</option>
                                        <option value="Otra">Otra</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                                <label>Hidratación<select name="hidratacion" aria-label="Hidratacion">
                                        <option value="">Seleccionar</option>
                                        <option value="Adecuada">Adecuada</option>
                                        <option value="Leve deshidratación">Leve deshidratación</option>
                                        <option value="Deshidratación moderada">Deshidratación moderada</option>
                                        <option value="Deshidratación severa">Deshidratación severa</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                                <label>Nutrición<select name="nutricion" aria-label="Nutricion">
                                        <option value="">Seleccionar</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Bajo peso">Bajo peso</option>
                                        <option value="Sobrepeso">Sobrepeso</option>
                                        <option value="Obesidad">Obesidad</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                                <label>Estado general<select name="estado_general" aria-label="Estado general">
                                        <option value="">Seleccionar</option>
                                        <option value="Estable">Estable</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Comprometido">Comprometido</option>
                                        <option value="No evaluable">No evaluable</option>
                                    </select></label>
                            </div>
                            <div class="ces-eva">
                                <label class="ces-eva-checkbox"><input type="checkbox" id="cesEvaHabilitada"
                                        name="eva_habilitada"> Registrar escala EVA (opcional)</label>
                                <label for="cesEva">Escala Visual Analógica (EVA)</label>
                                <input id="cesEva" name="eva" type="range" min="0" max="10"
                                    step="1" value="2" disabled aria-describedby="cesEvaValor">
                                <div class="ces-eva-ticks" aria-hidden="true">
                                    <span>0</span><span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span><span>8</span><span>9</span><span>10</span>
                                </div>
                                <output id="cesEvaValor" for="cesEva">EVA: No registrada</output>
                            </div>
                        </section>
                        <section class="ces-panel ces-exam-card" aria-labelledby="examenSegmentarioTitulo">
                            <h2 id="examenSegmentarioTitulo">Examen físico segmentario</h2>
                            <p class="ces-exam-hint">Información fija del caso clínico. No editable por el estudiante.
                            </p>
                            <div class="ces-exam-fields ces-segmentario-fields">
                                <label>Cabeza y cuello<input type="text" readonly
                                        value="{{ data_get($segmentario ?? [], 'cabeza_cuello', '') }}"
                                        placeholder="Dato pendiente del caso"></label>
                                <label>Tórax y abdomen<input type="text" readonly
                                        value="{{ data_get($segmentario ?? [], 'torax_abdomen', '') }}"
                                        placeholder="Dato pendiente del caso"></label>
                                <label>Extremidades y piel<input type="text" readonly
                                        value="{{ data_get($segmentario ?? [], 'extremidades_piel', '') }}"
                                        placeholder="Dato pendiente del caso"></label>
                                <label>Neurológico<input type="text" readonly
                                        value="{{ data_get($segmentario ?? [], 'neurologico', '') }}"
                                        placeholder="Dato pendiente del caso"></label>
                            </div>
                            <div class="ces-interpretacion"><label for="cesInterpretacion">Interpretación clínica del
                                    estudiante</label>
                                <textarea id="cesInterpretacion" name="interpretacion_clinica"
                                    placeholder="Escribe tu interpretación de los hallazgos del examen..."></textarea>
                            </div>
                        </section>
                    </div>
                    <!-- BOTONES INFERIORES -->
                    <div class="form-card ces-exam-footer">
                        <div class="form-actions">

                            <p class="save-message" id="cesMessage" role="status" aria-live="polite"></p>

                            <button type="button" class="btn btn-light" id="cesClearButton">
                                Limpiar
                            </button>

                            <a class="btn btn-light"
                                href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">
                                Anterior
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Guardar registro
                            </button>

                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
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
        <script>window.fichaThirdYear = {!! json_encode($thirdYearContext, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};</script>
    
    <script src="{{ asset('js/estudiante/tercero.js') }}" defer></script>
    <script src="{{ asset('js/estudiante/tercero-bridge.js') }}" defer></script>

<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>

</html>
