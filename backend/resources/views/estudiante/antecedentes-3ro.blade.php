<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Antecedentes | Ficha Clínica TP</title>

    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-3ro.css') }}">
</head>

<body>
    <div class="app app-shell" id="appShell">

        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP">
                </div>
                <span>Ficha Clínica TP</span>
            </div>

            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div>

                <a class="nav-link" href="{{ route('preview.estudiante.casos') }}">
                    Mis casos clínicos
                </a>

                <div class="nav-title">Atención clínica · 3.º medio</div>

                <a class="nav-link active ces-active"
                    href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">
                    Antecedentes
                </a>

                <a class="nav-link"
                    href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">
                    Signos vitales y antropometría
                </a>

                <a class="nav-link" href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">
                    Examen físico
                </a>

                <a class="nav-link" href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">
                    Citaciones y próximos controles
                </a>

                <div class="nav-title">Cuenta</div>

                <form action="{{ route('delivery.logout') }}" method="POST">@csrf<button type="submit" class="nav-link" style="width:100%;background:transparent;border:0;text-align:left;cursor:pointer">Cerrar sesión</button></form>
            </nav>
        </aside>

        <div class="main-area">

            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú"
                        aria-controls="sidebar" aria-expanded="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <span class="topbar-title">Antecedentes</span>
                </div>

                <div class="user-info">
                    <div class="user-details">
                        <strong>{{ auth()->user()?->name }}</strong>
                        <small>3.º medio · CESFAM</small>
                    </div>

                    <div class="user-avatar">E</div>
                </div>
            </header>

            <main class="content">

                <div class="page-heading">
                    <div>
                        <p class="eyebrow">
                            FICHA CLÍNICA EDUCATIVA · CESFAM
                        </p>

                        <h1>Antecedentes</h1>

                        <p class="muted">
                            Registra los antecedentes del paciente simulado.
                        </p>
                    </div>

                    <a class="btn btn-secondary" href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}">
                        ← Volver al caso
                    </a>
                </div>

                <div class="case-banner">
                    <div>
                        <span class="case-code">{{ $casoId }}</span>
                        <strong>Caso clínico asignado</strong>
                        <small>Paciente simulado · 3.º medio</small>
                    </div>

                    <div class="ces-case-tools">
                        <span class="case-status">En desarrollo</span>
                    </div>
                </div>

                <nav class="tabs ces-tabs" aria-label="Etapas de ficha clínica">

                    <a href="{{ route('preview.estudiante.caso', ['id' => $casoId]) }}">
                        Resumen
                    </a>

                    <a class="selected" aria-current="page"
                        href="{{ route('preview.estudiante.3ro.antecedentes-3ro', ['caso' => $casoId]) }}">
                        Antecedentes
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.signos-vitales-3ro', ['caso' => $casoId]) }}">
                        Signos vitales y antropometría
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.examen-fisico-3ro', ['caso' => $casoId]) }}">
                        Examen físico
                    </a>

                    <a href="{{ route('preview.estudiante.3ro.citaciones-3ro', ['caso' => $casoId]) }}">
                        Citaciones y próximos controles
                    </a>
                </nav>


                <form class="ces-form ces-ant-form" data-ces-form="antecedentes-3ro" data-caso="{{ $casoId }}">

                    <!-- MOTIVO DE CONSULTA -->

                    <!-- INFORMACIÓN GENERAL DEL PACIENTE -->
                    <section class="ces-panel ces-ant-panel">
                        <h2>Información general del paciente</h2>

                        <div class="ces-ant-general-grid">

                            <label class="ces-ant-field">
                                Motivo de consulta

                                <textarea name="motivo_consulta" rows="5" placeholder="Describe el motivo de consulta del paciente simulado"></textarea>
                            </label>

                            <label class="ces-ant-field">
                                Observaciones / Datos adicionales

                                <textarea name="observaciones" rows="5"
                                    placeholder="Registra observaciones complementarias o datos adicionales (opcional)"></textarea>
                            </label>

                        </div>
                    </section>


                    <!-- PATOLOGÍAS Y ALERGIAS -->
                    <div class="ces-ant-cards">

                        <!-- PATOLOGÍAS CRÓNICAS -->
                        <section class="ces-panel ces-ant-panel">

                            <div class="ces-ant-section-header">
                                <h2>Registro de patologías crónicas</h2>

                                <button type="button" class="btn btn-primary ces-ant-add" data-add-row="patologias"
                                    disabled>
                                    + Añadir
                                </button>
                            </div>

                            <div class="ces-ant-options ces-ant-options-inline">

                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_patologias" value="no">
                                    No refiere
                                </label>

                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_patologias" value="si">
                                    Sí refiere
                                </label>

                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_patologias" value="no_informado" checked>
                                    No informado en el caso
                                </label>

                            </div>

                            <div class="ces-ant-table-scroll">
                                <table class="ces-ant-table">
                                    <thead>
                                        <tr>
                                            <th>Patología crónica</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody id="cesPatologiasRows"></tbody>
                                </table>
                            </div>

                            <p class="ces-ant-help">
                                Registra únicamente las patologías crónicas
                                mencionadas en el caso clínico.
                            </p>

                        </section>


                        <!-- ALERGIAS -->
                        <section class="ces-panel ces-ant-panel">
                            <div class="ces-ant-section-header">
                                <h2>Alertas de alergias</h2>

                                <button type="button" class="btn btn-primary ces-ant-add" data-add-row="alergias"
                                    disabled>
                                    + Añadir
                                </button>
                            </div>

                            <div class="ces-ant-options ces-ant-options-inline">
                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_alergias" value="no">
                                    No refiere
                                </label>

                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_alergias" value="si">
                                    Sí refiere
                                </label>

                                <label class="ces-ant-radio">
                                    <input type="radio" name="tiene_alergias" value="no_informado" checked>
                                    No informado en el caso
                                </label>
                            </div>

                            <div class="ces-ant-table-scroll">
                                <table class="ces-ant-table">

                                    <thead>
                                        <tr>
                                            <th>Nombre de la alergia</th>
                                            <th>Reacción</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody id="cesAlergiasRows"></tbody>
                                </table>
                            </div>

                            <p class="ces-ant-help">
                                Registra las alergias conocidas que se
                                mencionan en el caso clínico.
                            </p>
                        </section>
                    </div>

                    <!-- MEDICAMENTOS -->
                    <section class="ces-panel ces-ant-panel ces-ant-wide">
                        <div class="ces-ant-section-header">
                            <h2>Medicamentos habituales</h2>

                            <button type="button" class="btn btn-primary ces-ant-add" data-add-row="medicamentos"
                                disabled>
                                + Añadir medicamento
                            </button>
                        </div>

                        <div class="ces-ant-options ces-ant-options-inline">
                            <label class="ces-ant-radio">
                                <input type="radio" name="tiene_medicamentos" value="no">
                                No refiere
                            </label>

                            <label class="ces-ant-radio">
                                <input type="radio" name="tiene_medicamentos" value="si">
                                Sí refiere
                            </label>

                            <label class="ces-ant-radio">
                                <input type="radio" name="tiene_medicamentos" value="no_informado" checked>
                                No informado en el caso
                            </label>
                        </div>

                        <div class="ces-ant-table-scroll">
                            <table class="ces-ant-table ces-ant-med-table">
                                <thead>
                                    <tr>
                                        <th>Medicamento</th>
                                        <th>Dosis</th>
                                        <th>Vía</th>
                                        <th>Frecuencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cesMedicamentosRows"></tbody>
                            </table>
                        </div>

                        <p class="ces-ant-help">
                            Ejemplos de formato: Metformina | 850 mg |
                            Oral | C/12 h. Completa solo la información
                            disponible en el caso clínico.
                        </p>
                    </section>


                    <!-- BOTONES INFERIORES -->
                    <div class="form-card ces-exam-footer">
                        <div class="form-actions">

                            <p class="save-message" id="cesMessage" role="status" aria-live="polite"></p>

                            <button type="button" class="btn btn-light" id="cesClearButton">
                                Limpiar
                            </button>

                            <a class="btn btn-light" href="{{ route('preview.estudiante.casos') }}">
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
    <script>
        // Habilita el detalle solo cuando se selecciona "Sí refiere medicamentos".
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-ces-form="antecedentes-3ro"]');
            if (!form) return;
            const radios = form.querySelectorAll('input[name="tiene_medicamentos"]');
            const detail = form.querySelector('textarea[name="medicamentos_habituales"]');
            if (!detail) return;
            const sync = () => {
                const selected = form.querySelector('input[name="tiene_medicamentos"]:checked');
                const enabled = selected && selected.value === 'si';
                detail.disabled = !enabled;
                detail.placeholder = enabled ? 'Registra los medicamentos indicados en el caso' :
                    'Selecciona Sí para especificar';
            };
            radios.forEach(radio => radio.addEventListener('change', sync));
            form.addEventListener('reset', () => requestAnimationFrame(sync));
            sync();
        });
    </script>


</body>

</html>
