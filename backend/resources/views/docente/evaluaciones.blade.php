<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Evaluaciones y retroalimentación | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-ajustes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-evaluaciones.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-registro-legible.css') }}">
<style>#evalPdf[hidden],#evalThirdPdf[hidden]{display:none!important}</style>
</head>

<body>
    <div class="app" id="app">
        @include('shared.navigation')
        <div class="main">
            <header class="topbar">
                <div class="topbar-left"><button class="menu-toggle" id="menuToggle" type="button"
                        aria-label="Mostrar u ocultar menú" aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button><strong>Evaluaciones y retroalimentación</strong></div>
                <div class="user-info">
                    <div><strong>{{ auth()->user()?->name ?? 'Docente' }}</strong><small>{{ auth()->user()?->hasRole('administrador') ? 'Panel administrativo · Funciones docentes' : 'Panel docente' }}</small></div>
                    <span class="avatar">{{ auth()->user()?->hasRole('administrador') ? 'A' : 'D' }}</span>
                </div>
            </header>
            <main class="content" id="evaluaciones">
                <div class="heading">
                    <div>
                        <h1>Evaluaciones y retroalimentación</h1>
                        <p class="subtitle">Revisa los resultados automáticos, los registros completos del estudiante y
                            añade retroalimentación docente.</p>
                    </div>
                </div>
                <div class="stats eval-stats">
                    <div class="stat">
                        <div class="stat-label">Evaluaciones disponibles</div>
                        <div class="stat-value" id="statTotal">0</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Pendientes</div>
                        <div class="stat-value" id="statPending">0</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Borradores</div>
                        <div class="stat-value" id="statDraft">0</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Finalizadas</div>
                        <div class="stat-value" id="statFinished">0</div>
                    </div>
                </div>
                <section class="panel">
                    <h2>Fichas para evaluar</h2>
                    <p class="panel-description">Las comprobaciones automáticas verifican datos observables en las fichas guardadas de 3.º y 4.º medio; los criterios clínicos deben revisarse y confirmarse por el docente.</p>
                    <div class="teacher-filters eval-filters">
                        <div class="form-field"><label for="evalSearch">Buscar estudiante o caso</label><input
                                id="evalSearch" placeholder="Nombre o código"></div>
                        <div class="form-field"><label for="evalJornada">Jornada de estudiantes</label><select id="evalJornada"><option value="diurno">Diurnos</option><option value="vespertino">Vespertinos (V3ro / V4to)</option></select></div>
                        <div class="form-field"><label for="evalLevel">Nivel</label><select id="evalLevel">
                                <option value="">Todos los niveles</option>
                                <option value="3.º medio">3.º medio</option>
                                <option value="4.º medio">4.º medio</option>
                            </select></div>
                        <div class="form-field"><label for="evalCourse">Curso</label><select id="evalCourse">
                                <option value="">Todos los cursos</option>
                            </select></div>
                        <div class="form-field"><label for="evalService">Servicio</label><select id="evalService">
                                <option value="">Todos</option>
                                <option value="urgencias">Urgencias</option>
                                <option value="cesfam">CESFAM</option>
                            </select></div>
                        <div class="form-field"><label for="evalStatus">Estado</label><select id="evalStatus">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="borrador">Borrador</option>
                                <option value="finalizada">Finalizada</option>
                            </select></div><button type="button" class="btn btn-secondary" id="evalClear">Limpiar
                            filtros</button>
                    </div>
                    <div class="table-wrap">
                        <table class="teacher-table">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
                                    <th>Caso clínico</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Resultado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="evalRows"></tbody>
                        </table>
                    </div>
                    <p id="evalCount" class="teacher-count" aria-live="polite"></p>
                </section>
                <section class="panel eval-editor" id="evalEditor" hidden>
                    <div class="eval-editor-head">
                        <div>
                            <h2 id="evalHeading">Pauta de evaluación</h2>
                            <p class="panel-description" id="evalContext"></p>
                        </div><a class="btn btn-secondary" id="evalPdf" href="#" hidden>Descargar pauta PDF</a> <button type="button" class="btn btn-secondary" id="evalBack">Volver al
                            listado</button>
                    </div>
                    <div class="eval-scale">
                        <strong>Escala de rendimiento de la pauta:</strong>
                        20% = 2,0 · 60% = 4,0 · 100% = 7,0
                        <small>La fórmula entre estos puntos es provisional y requiere confirmación del liceo.</small>
                    </div>
                    <div class="eval-summary">
                        <div><span class="eval-kicker">Criterios cumplidos</span><strong id="evalCompleted">0 /
                                9</strong></div>
                        <div><span class="eval-kicker">Porcentaje de logro</span><strong id="evalPercent">20%</strong>
                        </div>
                        <div><span class="eval-kicker">Nota</span><strong id="evalGrade">2,0</strong></div>
                        <div><span class="eval-kicker">Resultado</span><strong id="evalResult">En evaluación</strong>
                        </div>
                    </div>
                    <div class="eval-progress" aria-hidden="true">
                        <div id="evalProgressBar"></div>
                    </div>
                    <p class="eval-helper" id="evalAnswered">Esperando revisión de criterios pendientes.</p>
                    <p class="eval-helper">Las verificaciones automáticas comprueban existencia y completitud de campos, no la calidad de la atención. Puedes corregir cualquiera de sus resultados y debes revisar los criterios clínicos pendientes. Usa «Ver registro» para
                        consultar también textos y observaciones opcionales del estudiante.</p>
                    <div class="table-wrap">
                        <table class="teacher-table eval-criteria-table">
                            <thead>
                                <tr>
                                    <th>N.º</th>
                                    <th>Criterio</th>
                                    <th>Resultado</th>
                                    <th>Revisión y comentarios</th>
                                </tr>
                            </thead>
                            <tbody id="evalCriteria"></tbody>
                        </table>
                    </div>
                    <div class="form-field eval-feedback"><label for="evalFeedback">Comentarios y retroalimentación
                            del docente</label>
                        <textarea id="evalFeedback" rows="4"
                            placeholder="Escribe observaciones generales, fortalezas y aspectos por mejorar..."></textarea>
                    </div>
                    <p id="evalMessage" class="eval-message" role="status" aria-live="polite"></p>
                    <div class="eval-actions"><button type="button" class="btn btn-secondary"
                            id="evalSave">Guardar borrador</button><button type="button" class="btn btn-primary"
                            id="evalFinish">Finalizar evaluación</button></div>
                </section>
                <section class="panel" id="evalThirdEditor" hidden aria-labelledby="evalThirdHeading">
                    <div class="eval-editor-head"><div><h2 id="evalThirdHeading">Evaluación manual · CESFAM (3.º medio)</h2><p class="panel-description" id="evalThirdContext"></p></div>
                       <a class="btn btn-secondary" id="evalThirdPdf" href="#" hidden>Descargar pauta PDF</a> <button type="button" class="btn btn-secondary" id="evalThirdBack">Volver al listado</button></div>
                    <p class="panel-description">Revisa los registros desde «Revisión de fichas» y utiliza la pauta de atención primaria correspondiente. No se calcula una nota automáticamente.</p>
                    <div class="form-grid">
                      <div class="form-field"><label for="evalThirdGrade">Nota (2,0 a 7,0, opcional)</label><input id="evalThirdGrade" type="number" min="2" max="7" step="0.1" placeholder="Ej.: 5.5"></div>
                      <div class="form-field"><label for="evalThirdPercent">Porcentaje de logro (20–100, opcional)</label><input id="evalThirdPercent" type="number" min="20" max="100" step="0.1"></div>
                    </div>
                    <div class="form-field"><label for="evalThirdFeedback">Retroalimentación docente *</label><textarea id="evalThirdFeedback" rows="5" maxlength="10000" placeholder="Registra los criterios revisados, observaciones y recomendaciones."></textarea></div>
                    <p class="eval-message" id="evalThirdMessage" role="status"></p>
                    <div class="eval-actions"><button type="button" class="btn btn-secondary" id="evalThirdSave">Guardar borrador</button>
                        <button type="button" class="btn btn-primary" id="evalThirdFinish">Finalizar evaluación</button></div>
                </section>
                <div class="modal-overlay" id="evalConfirmModal" hidden>
                    <div class="modal-card confirm-modal" role="dialog" aria-modal="true"
                        aria-labelledby="evalModalTitle">
                        <div class="modal-header">
                            <h2 id="evalModalTitle">Finalizar evaluación</h2>
                        </div>
                        <div class="modal-body">
                            <p>Al finalizar, la evaluación quedará bloqueada de forma definitiva en el servidor. ¿Deseas continuar?
                            </p>
                        </div>
                        <div class="modal-footer"><button class="btn btn-secondary" type="button"
                                id="evalCancelConfirm">Cancelar</button><button class="btn btn-primary"
                                type="button" id="evalAcceptConfirm">Sí, finalizar</button></div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/docente/registro-legible.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-api.js') }}" defer></script>
    <script src="{{ asset('js/docente/docente-ui.js') }}" defer></script>
    <script src="{{ asset('js/docente/evaluaciones.js') }}" defer></script>
    <script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>
@include('shared.app-extras')
</body>

</html>
