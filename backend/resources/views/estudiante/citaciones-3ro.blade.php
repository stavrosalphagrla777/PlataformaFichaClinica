<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Citaciones y próximos controles | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-3ro.css') }}">
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
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">Signos vitales
                    y antropometría</a>
                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">Examen
                    físico</a>
                <a class="nav-link active ces-active"
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
                        class="topbar-title">Citaciones y próximos controles</span></div>
                <div class="user-info">
                    <div class="user-details"><strong>{{ auth()->user()?->name }}</strong><small>3.º medio · CESFAM</small></div>
                    <div class="user-avatar">E</div>
                </div>
            </header>
            <main class="content">
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA · CESFAM</p>
                        <h1>Citaciones y próximos controles</h1>
                        <p class="muted">Registra controles, educación al paciente y confirmación.</p>
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

                    <a href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">
                        Signos vitales y antropometría
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">
                        Examen físico
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}"
                        class="selected" aria-current="page">
                        Citaciones y próximos controles
                    </a>

                </nav>

                <form class="ces-form" data-ces-form="citaciones-3ro" data-caso="{{ $casoId }}">
                    <section class="ces-panel ces-agenda">
                        <h2>Ficha de agendamiento</h2>
                        <div class="ces-agenda-grid ces-agenda-first">
                            <label>Fecha / Hora<input type="datetime-local" name="fecha_hora"
                                    aria-label="Fecha y hora del control"></label>
                            <label>Tipo de control<input type="text" name="tipo_control"
                                    placeholder="Ej.: Control de Salud Cardiovascular"></label>
                            <label>Periodicidad<input type="text" name="periodicidad"
                                    placeholder="Ej.: En 3 meses"></label>
                        </div>
                        <div class="ces-agenda-grid ces-agenda-second">
                            <label>Profesional asignado<input type="text" name="profesional_asignado" value="{{ auth()->user()?->name }}" readonly
                                    title="Se completa con tu cuenta de estudiante"></label>
                            <label>Estado de citación<select name="estado_citacion">
                                    <option value="">Seleccionar estado</option>
                                    <option value="agendada">Citación agendada</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="reprogramada">Reprogramada</option>
                                    <option value="cancelada">Cancelada</option>
                                    <option value="realizada">Realizada</option>
                                </select></label>
                        </div>
                        <div class="ces-agenda-indicaciones"><label>Indicaciones al paciente
                                <textarea name="indicaciones" rows="6" placeholder="Escribe las indicaciones entregadas al paciente..."></textarea>
                            </label></div>
                    </section>
                    <section class="ces-panel">
                        <h2>Educación al paciente</h2>
                        <label>Educación entregada
                            <textarea name="educacion_paciente" placeholder="Describe la educación entregada al paciente simulado"></textarea>
                        </label>
                        <label class="ces-check"><input type="checkbox" name="educacion_confirmada" value="1">
                            Confirmo que realicé la educación al paciente</label>
                    </section>


                    <!-- BOTONES INFERIORES -->
                    <div class="form-card ces-exam-footer">
                        <div class="form-actions">

                            <p class="save-message" id="cesMessage" role="status" aria-live="polite"></p>

                            <button type="button" class="btn btn-light" id="cesClearButton">
                                Limpiar
                            </button>

                            <a class="btn btn-light"
                                href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">
                                Anterior
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Guardar borrador
                            </button>
                            <button type="button" class="btn btn-primary" id="cesSaveAndSubmit">
                                Guardar y entregar
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
</body>

</html>
