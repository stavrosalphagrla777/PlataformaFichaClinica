<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asignaciones | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ajustes.css') }}">
</head>

<body>
    <div class="app" id="app">
        @include('shared.navigation')
        <div class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle" type="button" aria-label="Mostrar u ocultar menú"
                        aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button>
                    <strong>Asignación de casos clínicos</strong>
                </div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content">
                <div class="heading">
                    <div>
                        <h1>Asignar caso a nivel o curso</h1>
                        <p class="subtitle">Selecciona el caso clínico y los cursos que tendrán acceso a la simulación.
                        </p>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('preview.docente.casos') }}">← Volver a casos</a>
                </div>
                <div class="assignment-layout">
                    <section class="panel">
                        <div class="assignment-section-heading">
                            <div>
                                <h2 id="formTitle">Nueva asignación</h2>
                                <p class="panel-description">Define el nivel educativo, curso y periodo de
                                    disponibilidad.</p>
                            </div>
                        </div>
                        <form id="assignmentForm">
                            <input type="hidden" id="editingId">
                            <div class="form-grid">
                                <div class="form-field span-2">
                                    <label for="caso">Caso clínico *</label>
                                    <select id="caso" required>
                                        <option value="">Seleccionar caso clínico</option>
                                        @foreach ($casos ?? [] as $caso)
                                            <option value="{{ $caso->codigo ?? ($caso['codigo'] ?? '') }}"
                                                data-nivel="{{ $caso->nivel ?? ($caso['nivel'] ?? 'Ambos niveles') }}"
                                                data-duracion="{{ $caso->duracion ?? ($caso['duracion'] ?? '') }}">
                                                {{ $caso->codigo ?? ($caso['codigo'] ?? '') }} ·
                                                {{ $caso->titulo ?? ($caso['titulo'] ?? '') }}</option>
                                        @endforeach
                                    </select>
                                    <small class="assignment-hint" id="caseHint">Selecciona un caso disponible.</small>
                                    <small class="assignment-hint">Se muestran los casos publicados por todos los docentes. Solo administras las asignaciones que tú realizas.</small>
                                </div>
                                <div class="form-field"><label for="nivel">Nivel educativo *</label><select
                                        id="nivel" required>
                                        <option value="">Seleccionar nivel</option>
                                        
                                        <option value="3.º medio">3.º medio</option><option value="4.º medio">4.º medio</option>
                                    </select></div>
                                <div class="form-field"><label for="curso">Curso *</label><select id="curso"
                                        required>
                                        <option value="">Selecciona primero el nivel</option>
                                    </select></div>
                                <div class="form-field"><label for="inicio">Inicio de disponibilidad *</label><input
                                        type="datetime-local" id="inicio" required></div>
                                <div class="form-field"><label for="termino">Fin de disponibilidad
                                        *</label><input type="datetime-local" id="termino" required></div>
                                <div class="form-field span-2">
                                    <label for="duracion">Duración del caso</label>
                                    <input type="number" id="duracion" min="1" max="10080" step="1" required placeholder="Selecciona un caso"
                                        aria-describedby="duracionAyuda">
                                    <small id="duracionAyuda" class="assignment-hint">Duración en minutos. Definida al crear el caso clínico; puedes ajustarla para esta asignación antes de que los estudiantes la inicien.</small>
                                </div>
                                <fieldset class="assignment-status span-2">
                                    <legend>Estado de la asignación *</legend>
                                    <div class="assignment-status-options"><label><input type="radio" name="estado"
                                                value="Activa" checked><span>Activa</span></label><label><input
                                                type="radio" name="estado"
                                                value="Programada"><span>Programada</span></label><label><input
                                                type="radio" name="estado"
                                                value="Inactiva"><span>Inactiva</span></label></div>
                                </fieldset>
                                <div class="form-field span-2"><label for="observaciones">Observaciones para el
                                        curso</label>
                                    <textarea id="observaciones" rows="5" placeholder="Indicaciones generales de la actividad (opcional)..."></textarea>
                                </div>
                            </div>
                            <p id="assignmentMessage" class="teacher-feedback" role="status" aria-live="polite">
                            </p>
                            <div class="form-actions"><button class="btn btn-secondary" id="cancelEdit"
                                    type="button">Limpiar</button><button class="btn btn-primary"
                                    id="saveAssignment" type="submit">Guardar asignación</button></div>
                        </form>
                    </section>
                    <aside class="panel assignment-aside">
                        <h2>Resumen de la asignación</h2>
                        <p class="panel-description">Verifica los datos antes de guardar.</p>
                        <div class="teacher-summary"><span>Caso</span><strong
                                id="sumCaso">—</strong><span>Nivel</span><strong
                                id="sumNivel">—</strong><span>Curso</span><strong
                                id="sumCurso">—</strong><span>Periodo</span><strong
                                id="sumPeriodo">—</strong><span>Duración</span><strong id="sumDuracion">—</strong><span>Estado</span><strong id="sumEstado">Activa</strong>
                        </div>
                        <div class="teacher-info">La disponibilidad para estudiantes dependerá del estado y del periodo
                            configurado. Las fechas se validan antes de guardar.</div>
                    </aside>
                </div>
                <section class="panel assignment-list-panel">
                    <div class="assignment-list-header">
                        <div>
                            <h2>Asignaciones existentes</h2>
                            <p class="panel-description">Casos disponibles por nivel y curso</p>
                        </div>
                        <div class="assignment-list-tools"><input id="searchAssignments" type="search"
                                aria-label="Buscar asignaciones" placeholder="Buscar caso o curso"><select
                                id="filterStatus" aria-label="Filtrar por estado">
                                <option value="">Todos los estados</option>
                                <option>Activa</option>
                                <option>Programada</option>
                                <option>Inactiva</option>
                            </select></div>
                    </div>
                    <div class="table-wrap">
                        <table class="teacher-table">
                            <thead>
                                <tr>
                                    <th>Caso</th>
                                    <th>Curso</th>
                                    <th>Periodo</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="assignmentRows">
                                <tr>
                                    <td colspan="6" class="assignment-empty">No hay asignaciones registradas.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p id="assignmentCount" class="assignment-count" aria-live="polite"></p>
                </section>
            </main>
        </div>
    </div>
    <!-- Confirmación visual para eliminar una asignación. No elimina hasta confirmar. -->
    <div class="modal-overlay" id="deleteAssignmentModal" hidden>
        <div class="modal-card confirm-modal" role="dialog" aria-modal="true"
            aria-labelledby="deleteAssignmentTitle" aria-describedby="deleteAssignmentDescription">
            <div class="confirm-modal-icon" aria-hidden="true">!</div>
            <h2 id="deleteAssignmentTitle">Eliminar asignación</h2>
            <p id="deleteAssignmentDescription">¿Estás seguro de que deseas eliminar esta asignación? Esta acción no se
                puede deshacer.</p>
            <div class="confirm-modal-actions">
                <button type="button" class="btn btn-secondary" id="cancelDeleteAssignment">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAssignment">Sí,
                    eliminar</button>
            </div>
        </div>
    </div>
    <div class="modal-overlay" id="clearAssignmentModal" hidden>
      <div class="modal-card confirm-modal" role="dialog" aria-modal="true" aria-labelledby="clearAssignmentTitle">
        <div class="confirm-modal-icon" aria-hidden="true">!</div>
        <h2 id="clearAssignmentTitle">Limpiar formulario</h2>
        <p>¿Deseas descartar los datos ingresados y restablecer el formulario? No se eliminarán asignaciones guardadas.</p>
        <div class="confirm-modal-actions"><button type="button" class="btn btn-secondary" id="cancelClearAssignment">Cancelar</button><button type="button" class="btn btn-primary" id="confirmClearAssignment">Sí, limpiar</button></div>
      </div>
    </div>
    <script src="{{ asset('js/docente/docente-api.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-ui.js') }}" defer></script>
    <script src="{{ asset('js/docente/asignaciones.js') }}" defer></script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
</body>

</html>
