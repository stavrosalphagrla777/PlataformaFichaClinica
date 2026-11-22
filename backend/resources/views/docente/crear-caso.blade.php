<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear caso clínico | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ajustes.css') }}">
</head>

<body>
    <div class="app" id="app">
        @include('shared.navigation')
        <div class="main">
            <header class="topbar">
                <div class="topbar-left"><button class="menu-toggle" id="menuToggle" type="button"
                        aria-label="Mostrar u ocultar menú" aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button><strong>Crear caso clínico</strong></div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content">
                <div class="heading">
                    <div>
                        <h1>Crear caso clínico</h1>
                        <p class="subtitle">Define el caso educativo, su clasificación y los antecedentes del paciente
                            simulado.</p>
                    </div><a class="btn btn-secondary" href="{{ route('preview.docente.casos') }}">← Volver al
                        listado</a>
                </div>
                <div class="teacher-layout">
                    <section class="panel">
                        <form id="caseForm">
                            <h2>Información del caso</h2>
                            <div class="form-grid">
                                <div class="form-field"><label for="titulo">Título del caso *</label><input
                                        id="titulo" required maxlength="160"
                                        placeholder="Ej.: Atención de urgencia respiratoria"></div>
                                <div class="form-field"><label for="codigo">Código del caso</label><input id="codigo" value="Se asigna automáticamente" readonly aria-describedby="codigoHelp"><small id="codigoHelp">El sistema asigna un código único CAS-000001, CAS-000002, etc. al guardar.</small></div>
                                <div class="form-field"><label for="nivel">Nivel educativo *</label><select
                                        id="nivel" required>
                                        
                                        <option value="3.º medio">3.º medio</option><option value="4.º medio" selected>4.º medio</option>
                                        
                                    </select></div>
                                <div class="form-field"><label for="tipo">Tipo de actividad *</label><select
                                        id="tipo">
                                        <option>Examen</option>
                                        <option>Tarea</option>
                                        <option>Material de apoyo</option>
                                    </select></div>
                                <div class="form-field"><label for="estado">Estado del caso</label><select
                                        id="estado">
                                        <option>Borrador</option>
                                        <option>Publicado</option>
                                        <option>Archivado</option>
                                    </select></div>
                                <div class="form-field"><label for="servicio">Contexto clínico</label><select
                                        id="servicio">
                                        
                                        <option>Servicio de urgencias</option><option>Atención primaria (CESFAM)</option>
                                        
                                    </select></div>
                                <div class="form-field">
                                    <label for="duracion">Duración máxima del caso (minutos)</label>
                                    <input type="number" id="duracion" name="duracion" min="1" max="10080"
                                        step="1" value="60" placeholder="Ej.: 60">
                                    <small>
                                        Tiempo disponible desde que el estudiante inicia el caso.
                                        Al finalizar, la ficha quedará bloqueada para edición.
                                    </small>
                                </div>
                                <div class="form-field span-2"><label for="descripcion">Descripción e instrucciones del
                                        caso *</label>
                                    <div class="clinical-editor" id="editor-descripcion"
                                        data-placeholder="Describe el escenario de simulación y las instrucciones para el estudiante.">
                                    </div>
                                    <textarea id="descripcion" name="descripcion" class="clinical-editor-value" required aria-label="descripcion"></textarea>
                                </div>
                            </div>
                            <hr class="teacher-divider">
                            <h2>Paciente simulado</h2>
                            <div class="form-grid">
                                <div class="form-field"><label for="paciente">Nombre del paciente</label><input
                                        id="paciente" maxlength="140"></div>
                                <div class="form-field"><label for="edad">Edad</label><input id="edad"
                                        type="number" min="0" max="120"></div>
                                <div class="form-field"><label for="sexo">Sexo</label><select id="sexo">
                                        <option value="">Seleccionar</option>
                                        <option>Femenino</option>
                                        <option>Masculino</option>
                                        <option>Otro / No especificado</option>
                                    </select></div>
                                <div class="form-field"><label for="prevision">Previsión de salud</label><select
                                        id="prevision">
                                        <option value="">Seleccionar</option>
                                        <option>FONASA</option>
                                        <option>ISAPRE</option>
                                        <option>Otra</option>
                                        <option>No informado</option>
                                    </select></div>
                                <div class="form-field" id="esiField"><label for="esi">Prioridad
                                        ESI</label><select id="esi">
                                        <option value="">No asignada</option>
                                        <option>ESI 1</option>
                                        <option>ESI 2</option>
                                        <option>ESI 3</option>
                                        <option>ESI 4</option>
                                        <option>ESI 5</option>
                                    </select></div>
                                <div class="form-field span-2"><label for="antecedentes">Antecedentes clínicos del
                                        caso</label>
                                    <div class="clinical-editor" id="editor-antecedentes"
                                        data-placeholder="Antecedentes, síntomas, alergias, medicamentos, exámenes disponibles y datos relevantes.">
                                    </div>
                                    <textarea id="antecedentes" name="antecedentes" class="clinical-editor-value" aria-label="antecedentes"></textarea>
                                </div>
                            </div>
                            <section class="case-segment-panel" aria-labelledby="caseSegmentTitle">
                                <h2 id="caseSegmentTitle">Examen físico segmentario · Hallazgos del docente</h2>
                                <p class="panel-description">Información del escenario clínico que verá el estudiante. En 3.º medio será de solo lectura; en 4.º medio se mostrará como referencia antes de que el estudiante registre sus propios hallazgos.</p>
                                <div class="form-grid">
                                    <div class="form-field"><label for="seg_cabeza_cuello">Cabeza y cuello</label><textarea id="seg_cabeza_cuello" rows="3" maxlength="2500" placeholder="Hallazgos del caso..." ></textarea></div>
                                    <div class="form-field"><label for="seg_torax_abdomen">Tórax y abdomen</label><textarea id="seg_torax_abdomen" rows="3" maxlength="2500" placeholder="Hallazgos del caso..."></textarea></div>
                                    <div class="form-field"><label for="seg_extremidades_piel">Extremidades y piel</label><textarea id="seg_extremidades_piel" rows="3" maxlength="2500" placeholder="Hallazgos del caso..."></textarea></div>
                                    <div class="form-field"><label for="seg_neurologico">Neurológico</label><textarea id="seg_neurologico" rows="3" maxlength="2500" placeholder="Hallazgos del caso..."></textarea></div>
                                </div>
                            </section>
                            <div class="form-actions">
                                <button class="btn btn-secondary" type="button" id="resetButton">
                                    Restablecer
                                </button>
                                <button class="btn btn-primary"type="submit">Guardar caso</button>
                            </div>
                            <p id="formMessage" class="teacher-feedback" role="status" aria-live="polite"></p>
                        </form>
                    </section>
                    <aside class="panel">
                        <h2>Resumen del caso</h2>
                        <p class="panel-description">Revisa la configuración antes de guardar.</p>
                        <div class="teacher-summary">
                            <span>Título</span>
                            <strong id="sumTitulo">—</strong>
                            <span>Nivel</span>
                            <strong id="sumNivel">4.º medio</strong>
                            <span>Actividad</span>
                            <strong id="sumTipo">Examen</strong>
                            <span>Estado</span>
                            <strong id="sumEstado">Borrador</strong>
                            <span>Contexto</span>
                            <strong id="sumServicio">Servicio de urgencias</strong>
                            <span>Duración máxima</span>
                            <strong id="sumDuracion">60 minutos</strong>
                        </div>
                        <div class="teacher-info">Los casos de examen y tarea se podrán asignar a estudiantes y cursos
                            con un plazo de entrega. El material de apoyo se distinguirá de las actividades evaluadas.
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
    <!-- Modal de confirmación para restablecer -->
    <div class="modal-overlay" id="resetModal" hidden>
        <div class="modal-card confirm-modal" role="dialog" aria-modal="true" aria-labelledby="resetModalTitle"
            aria-describedby="resetModalDescription">

            <div class="confirm-modal-icon">!</div>

            <h2 id="resetModalTitle">Restablecer formulario</h2>

            <p id="resetModalDescription">
                ¿Estás seguro de que deseas restablecer los datos
                ingresados en el caso clínico? Se descartarán los
                cambios que no hayas guardado.
            </p>

            <div class="confirm-modal-actions">
                <button type="button" class="btn btn-secondary" id="cancelReset">
                    Cancelar
                </button>

                <button type="button" class="btn btn-danger" id="confirmReset">
                    Sí, restablecer
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="{{ asset('js/docente/docente-api.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-ui.js') }}" defer></script>
    <script src="{{ asset('js/docente/crear-caso.js') }}" defer></script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
@include('shared.app-extras')
</body>

</html>
