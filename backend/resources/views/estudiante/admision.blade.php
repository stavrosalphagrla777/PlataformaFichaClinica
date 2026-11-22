
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admisión | Ficha Clínica TP</title>

    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <!-- Contexto autorizado de 4.º medio; nunca contiene datos de otros estudiantes. -->
    <script>window.__FICHA_4TO = @json($ficha4toContext ?? null);</script>
    <link rel="stylesheet" href="{{ asset('css/estudiante-confirmacion-4to.css') }}">
    <script src="{{ asset('js/estudiante/4to-backend-bridge.js') }}"></script>
</head>

<body>
    <div class="app app-shell" id="appShell">

        <!-- MENÚ LATERAL -->
        @include('estudiante.partials.sidebar-4to')

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

                    <span class="topbar-title">Admisión</span>
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

                        <h1>Admisión del paciente</h1>

                        <p class="muted">
                            Registra los datos iniciales del paciente simulado.
                        </p>
                    </div>

                    <a
                        class="btn btn-secondary"
                        href="{{ url('/estudiante/casos') }}"
                    >
                        ← Volver a mis casos
                    </a>
                </div>

                <!-- INFORMACIÓN DEL CASO -->
                <div class="case-banner">
                    <div>
                        <span class="case-code" id="caseCode">CAS-001</span>

                        <strong id="caseTitle">
                            Alteración neurológica aguda
                        </strong>

                        <small>
                            Paciente simulado · Información de admisión
                        </small>
                    </div>

                    <span class="case-status">En desarrollo</span>
                </div>

                <!-- PESTAÑAS -->
                
                <div class="tabs" aria-label="Etapas de ficha clínica">
                    <a id="tabResumen" href="#">Resumen</a>
                    <a class="selected" id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a>
                    <a id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a>
                    <a id="tabSignos" href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a>
                    <a id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}" aria-current="page">ABCDE</a>
                    <a id="tabAnamnesis" href="{{ route('preview.estudiante.anamnesis') }}">Anamnesis</a>
                    <a id="tabExaFisico" href="#">Examen físico</a>
                    <a id="tabProcedimientos" href="#">Procedimientos</a>
                    <a id="tabMedicamentos" href="#">Medicamentos</a>
                    <a id="tabExamenes" href="#">Exámenes</a>
                    <a id="tabAccEnfermeria" href="#">Acciones de enfermería</a>
                    <a id="tabDestino" href="#">Destino</a>
                </div>


                <!-- FORMULARIO DE ADMISIÓN -->
                <form id="admissionForm" class="form-card" novalidate>

                    <!-- DATOS PERSONALES -->
                    <section class="form-section">
                        <h2>♙ Datos personales</h2>

                        <div class="form-grid cols-4">
                            <label>
                                Nombre completo <em>*</em>

                                <input
                                    name="nombre"
                                    type="text"
                                    required
                                    placeholder="Nombre y apellidos"
                                >
                            </label>

                            <label>
                                RUT <em>*</em>

                                <input
                                    name="rut"
                                    type="text"
                                    required
                                    placeholder="12.345.678-9"
                                >
                            </label>

                            <label>
                                Fecha de nacimiento (opcional)

                                <input
                                    name="nacimiento"
                                    id="nacimiento"
                                    type="date"
                                >
                            </label>

                            <label>
                                Edad

                                <input
                                    name="edad"
                                    id="edad"
                                    type="number"
                                    min="0"
                                    max="120"
                                    placeholder="Ingresar edad"
                                >
                            </label>
                        </div>

                        <div class="form-grid cols-3">
                            <fieldset class="sex-field">
                                <legend>Sexo <em>*</em></legend>

                                <div class="radio-row">
                                    <label>
                                        <input
                                            type="radio"
                                            name="sexo"
                                            value="Femenino"
                                            required
                                        >
                                        Femenino
                                    </label>

                                    <label>
                                        <input
                                            type="radio"
                                            name="sexo"
                                            value="Masculino"
                                        >
                                        Masculino
                                    </label>
                                </div>
                            </fieldset>

                            <label>
                                Dirección <em>*</em>

                                <input
                                    name="direccion"
                                    type="text"
                                    required
                                    placeholder="Calle, número, comuna"
                                >
                            </label>

                            <label>
                                Teléfono <em>*</em>

                                <input
                                    name="telefono"
                                    type="tel"
                                    required
                                    placeholder="+56 9 1234 5678"
                                >
                            </label>
                        </div>

                        <div class="form-grid cols-4">
                            <label>
                                Previsión de salud <em>*</em>

                                <select name="prevision" required>
                                    <option value="">Seleccionar</option>
                                    <option>FONASA</option>
                                    <option>ISAPRE</option>
                                    <option>Particular</option>
                                    <option>Otra</option>
                                </select>
                            </label>

                            <label>
                                Número de afiliado

                                <input
                                    name="afiliado"
                                    type="text"
                                    placeholder="Opcional"
                                >
                            </label>

                            <label>
                                Sistema de salud

                                <select name="sistema">
                                    <option value="">Seleccionar</option>
                                    <option>FONASA A</option>
                                    <option>FONASA B</option>
                                    <option>FONASA C</option>
                                    <option>FONASA D</option>
                                    <option>ISAPRE</option>
                                    <option>Otro</option>
                                </select>
                            </label>

                            <label>
                                Estado civil

                                <select name="estado_civil">
                                    <option value="">Seleccionar</option>
                                    <option>Soltero/a</option>
                                    <option>Casado/a</option>
                                    <option>Conviviente</option>
                                    <option>Divorciado/a</option>
                                    <option>Viudo/a</option>
                                </select>
                            </label>
                        </div>
                    </section>

                    <!-- CONTACTO DE EMERGENCIA -->
                    <section class="form-section">
                        <h2>♧ Contacto de emergencia</h2>

                        <div class="form-grid cols-4">
                            <label>
                                Nombre del contacto <em>*</em>

                                <input
                                    name="contacto_nombre"
                                    type="text"
                                    required
                                >
                            </label>

                            <label>
                                Parentesco <em>*</em>

                                <select name="parentesco" required>
                                    <option value="">Seleccionar</option>
                                    <option>Madre</option>
                                    <option>Padre</option>
                                    <option>Pareja</option>
                                    <option>Hermano/a</option>
                                    <option>Hijo/a</option>
                                    <option>Otro</option>
                                </select>
                            </label>

                            <label>
                                Teléfono de contacto <em>*</em>

                                <input
                                    name="contacto_telefono"
                                    type="tel"
                                    required
                                >
                            </label>

                            <label>
                                Dirección del contacto

                                <input
                                    name="contacto_direccion"
                                    type="text"
                                >
                            </label>
                        </div>
                    </section>

                    <!-- ALERGIAS -->
                    <section class="form-section">
                        <h2>⚠ Alergias</h2>

                        <div class="form-grid cols-3">
                            <fieldset class="allergy-detail">
                                <legend>Detalles de alergias (opcional)</legend>
                                <p class="helper">Primero selecciona el tipo de alergia. Utiliza este espacio si necesitas especificar el medicamento, alimento u otra sustancia.</p>
                                <textarea name="alergias" id="allergyDetails" rows="4" placeholder="Ej.: penicilina, maní, tipo de látex u otra sustancia"></textarea>
                            </fieldset>

                            <fieldset>
                                <legend>Tipo de alergia</legend>

                                <p class="helper">Puedes marcar una o varias alternativas.</p>
                                <div class="check-column">
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="tipo_alergia[]"
                                            value="Medicamentos"
                                        >
                                        Medicamentos
                                    </label>

                                    <label>
                                        <input
                                            type="checkbox"
                                            name="tipo_alergia[]"
                                            value="Alimentos"
                                        >
                                        Alimentos
                                    </label>

                                    <label>
                                        <input
                                            type="checkbox"
                                            name="tipo_alergia[]"
                                            value="Látex"
                                        >
                                        Látex
                                    </label>

                                    <label>
                                        <input
                                            type="checkbox"
                                            name="tipo_alergia[]"
                                            value="Otras"
                                            id="allergyOther"
                                        >
                                        Otras
                                    </label>
                                </div>
                            </fieldset>

                            <fieldset class="allergy-reaction">
                                <legend>Reacción alérgica</legend>
                                <div class="radio-row" role="group" aria-label="Presencia de reacción">
                                    <label><input type="radio" name="reaccion_refiere" value="si"> Sí refiere</label>
                                    <label><input type="radio" name="reaccion_refiere" value="no"> No refiere</label>
                                </div>
                                <label for="allergyReactionDetails" class="helper">Descripción de la reacción (opcional)</label>
                                <textarea name="reaccion" id="allergyReactionDetails" rows="4" placeholder="Ej.: urticaria, disnea, náuseas o fecha de la reacción"></textarea>
                            </fieldset>
                        </div>
                    </section>

                    <!-- ANTECEDENTES CLÍNICOS -->
                    <section class="form-section">
                        <div class="form-grid cols-3">
                            <label>
                                Antecedentes mórbidos

                                <textarea
                                    name="morbidos"
                                    rows="5"
                                    placeholder="Enfermedades previas o crónicas"
                                ></textarea>
                            </label>

                            <label>
                                Antecedentes quirúrgicos

                                <textarea
                                    name="quirurgicos"
                                    rows="5"
                                    placeholder="Cirugías y fechas"
                                ></textarea>
                            </label>

                            <label>
                                Antecedentes familiares

                                <textarea
                                    name="familiares"
                                    rows="5"
                                    placeholder="Enfermedades familiares relevantes"
                                ></textarea>
                            </label>
                        </div>
                    </section>

                    <!-- MEDICAMENTOS HABITUALES -->
                    <section class="form-section">
                        <div class="section-heading">
                            <h2>▣ Medicamentos habituales</h2>

                            <button
                                type="button"
                                class="btn btn-outline"
                                id="addMedication"
                            >
                                + Agregar medicamento
                            </button>
                        </div>

                        <div class="table-scroll">
                            <table class="med-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Medicamento</th>
                                        <th>Dosis</th>
                                        <th>Vía</th>
                                        <th>Frecuencia</th>
                                        <th>Indicación</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tbody id="medicationRows"></tbody>
                            </table>
                        </div>

                        <p class="helper">
                            Agrega los medicamentos habituales que correspondan
                            al caso simulado.
                        </p>
                    </section>

                    <!-- ACCIONES DEL FORMULARIO -->
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
                            href="{{ url('/estudiante/casos') }}"
                        >
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Guardar e ingresar a Triage →
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA LIMPIAR FORMULARIO -->
    <div class="modal-overlay" id="clearModal" hidden>
        <div
            class="modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="clearModalTitle"
            aria-describedby="clearModalDescription"
        >
            <div class="modal-icon">
                !
            </div>

            <h2 id="clearModalTitle">
                Limpiar formulario
            </h2>

            <p id="clearModalDescription">
                ¿Estás seguro de que deseas limpiar todos los datos
                ingresados en el formulario de admisión?
                Esta acción descartará los cambios no guardados.
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
            // MENÚ LATERAL
            const shell = document.getElementById('appShell');
            const toggle = document.getElementById('menuToggle');

            toggle.addEventListener('click', function () {
                const hidden = shell.classList.toggle('sidebar-hidden');

                toggle.setAttribute('aria-expanded', String(!hidden));
            });

            // ELEMENTOS DEL FORMULARIO
            const form = document.getElementById('admissionForm');
            const tbody = document.getElementById('medicationRows');
            const message = document.getElementById('saveMessage');

            // IDENTIFICACIÓN DEL CASO
            
            // IDENTIFICACIÓN DEL CASO
            const params = new URLSearchParams(window.location.search);

            const caseId = window.__FICHA_4TO?.codigo || (/^CAS-00[1-5]$/.test(params.get('caso') || '')
                ? params.get('caso')
                : 'CAS-001');

            const titles = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };

            document.getElementById('caseCode').textContent = caseId;
            document.getElementById('caseTitle').textContent = window.__FICHA_4TO?.titulo || titles[caseId] || 'Caso clínico';

            const key = 'ficha-tp-admision-demo-' + caseId;

            // NAVEGACIÓN ENTRE VISTAS
            const triageUrl =
                @json(route('preview.estudiante.triage')) +
                '?caso=' +
                encodeURIComponent(caseId);

            const detailUrl =
                @json(url('/estudiante/caso')) +
                '/' +
                encodeURIComponent(caseId);

            document.getElementById('tabTriage').href = triageUrl;
            document.getElementById('tabResumen').href = detailUrl;


            // AGREGAR MEDICAMENTOS
            function addMedication(data = {}) {
                const tr = document.createElement('tr');

                const fields = [
                    'medicamento',
                    'dosis',
                    'via',
                    'frecuencia',
                    'indicacion'
                ];

                const number = document.createElement('td');
                tr.appendChild(number);

                fields.forEach(function (field) {
                    const td = document.createElement('td');
                    const input = document.createElement('input');

                    input.type = 'text';
                    input.dataset.field = field;
                    input.value = data[field] || '';
                    input.placeholder = ({
                        medicamento:'Ej.: Paracetamol',
                        dosis:'Ej.: 500 mg',
                        via:'Ej.: Oral',
                        frecuencia:'Ej.: Cada 8 horas',
                        indicacion:'Ej.: Control del dolor'
                    })[field] || '';
                    input.setAttribute('aria-label', field);

                    td.appendChild(input);
                    tr.appendChild(td);
                });

                const actions = document.createElement('td');
                const remove = document.createElement('button');

                remove.type = 'button';
                remove.className = 'remove-med';
                remove.textContent = 'Eliminar';

                remove.addEventListener('click', function () {
                    tr.remove();
                    renumber();
                });

                actions.appendChild(remove);
                tr.appendChild(actions);

                tbody.appendChild(tr);
                renumber();
            }

            // RENUMERAR MEDICAMENTOS
            function renumber() {
                Array.from(tbody.rows).forEach(function (row, i) {
                    row.cells[0].textContent = i + 1;
                });
            }

            // CAPTURAR DATOS DEL FORMULARIO
            function capture() {
                const data = {};

                Array.from(form.elements).forEach(function (el) {
                    if (!el.name) {
                        return;
                    }

                    if (el.type === 'checkbox') {
                        if (!data[el.name]) {
                            data[el.name] = [];
                        }

                        if (el.checked) {
                            data[el.name].push(el.value);
                        }
                    } else if (el.type === 'radio') {
                        if (el.checked) {
                            data[el.name] = el.value;
                        }
                    } else if (!el.name.endsWith('[]')) {
                        data[el.name] = el.value;
                    }
                });

                data.medicamentos = Array.from(tbody.rows).map(function (row) {
                    return Object.fromEntries(
                        Array.from(
                            row.querySelectorAll('[data-field]')
                        ).map(function (el) {
                            return [el.dataset.field, el.value];
                        })
                    );
                });

                return data;
            }

            // RESTAURAR DATOS GUARDADOS
            function restore(data) {
                Array.from(form.elements).forEach(function (el) {
                    if (!el.name) {
                        return;
                    }

                    if (el.type === 'checkbox') {
                        el.checked = (data[el.name] || []).includes(el.value);
                    } else if (el.type === 'radio') {
                        el.checked = data[el.name] === el.value;
                    } else if (
                        Object.prototype.hasOwnProperty.call(data, el.name)
                    ) {
                        el.value = data[el.name];
                    }
                });

                tbody.innerHTML = '';

                (data.medicamentos || []).forEach(addMedication);
            }

            // BOTÓN AGREGAR MEDICAMENTO
            document.getElementById('addMedication').addEventListener(
                'click',
                function () {
                    addMedication();
                }
            );

            // CALCULAR EDAD AUTOMÁTICAMENTE O PERMITIR INGRESO MANUAL
            const nacimientoInput = document.getElementById('nacimiento');
            const edadInput = document.getElementById('edad');

            nacimientoInput.addEventListener('change', function () {
                // Si no se conoce la fecha de nacimiento,
                // se conserva la edad ingresada manualmente.
                if (!this.value) {
                    return;
                }

                const today = new Date();
                const born = new Date(this.value + 'T12:00:00');

                let age = today.getFullYear() - born.getFullYear();

                if (
                    today.getMonth() < born.getMonth() ||
                    (
                        today.getMonth() === born.getMonth() &&
                        today.getDate() < born.getDate()
                    )
                ) {
                    age--;
                }

                if (age >= 0 && age <= 120) {
                    edadInput.value = age;
                }
            });


            // MODAL DE CONFIRMACIÓN PARA LIMPIAR FORMULARIO

            const clearModal = document.getElementById('clearModal');
            const clearButton = document.getElementById('clearButton');
            const cancelClear = document.getElementById('cancelClear');
            const confirmClear = document.getElementById('confirmClear');

            let previousFocus = null;

            // Abrir modal
            clearButton.addEventListener('click', function () {
                previousFocus = document.activeElement;

                clearModal.hidden = false;
                document.body.style.overflow = 'hidden';

                cancelClear.focus();
            });

            // Cerrar modal
            function closeClearModal() {
                clearModal.hidden = true;
                document.body.style.overflow = '';

                if (previousFocus) {
                    previousFocus.focus();
                }
            }

            // Cancelar limpieza
            cancelClear.addEventListener('click', function () {
                closeClearModal();
            });

            // Confirmar limpieza
            confirmClear.addEventListener('click', function () {
                form.reset();
                tbody.innerHTML = '';

                document.getElementById('edad').value = '';

                message.textContent = 'Formulario limpio.';

                closeClearModal();
            });

            // Cerrar al hacer clic fuera del modal
            clearModal.addEventListener('click', function (event) {
                if (event.target === clearModal) {
                    closeClearModal();
                }
            });

            // Cerrar con Escape y controlar el foco
            document.addEventListener('keydown', function (event) {
                if (clearModal.hidden) {
                    return;
                }

                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeClearModal();
                }

                if (event.key === 'Tab') {
                    const first = cancelClear;
                    const last = confirmClear;

                    if (
                        event.shiftKey &&
                        document.activeElement === first
                    ) {
                        event.preventDefault();
                        last.focus();
                    } else if (
                        !event.shiftKey &&
                        document.activeElement === last
                    ) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            });


            // GUARDAR FORMULARIO
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!form.reportValidity()) {
                    return;
                }

                try {
                    localStorage.setItem(key, JSON.stringify(capture()));
                    message.textContent = 'Guardando admisión...';
                    window.Ficha4toBridge?.flush().then(() => {
                        message.textContent = 'Admisión guardada correctamente.';
                    }).catch(error => { message.textContent = 'No fue posible guardar admisión: ' + error.message; });
                } catch (error) {
                    message.textContent =
                        'No se pudo guardar el registro.';
                }
            });

            // Autoguardado real de borradores: se conserva incluso si se abre Resumen
            // antes de pulsar «Guardar». No interrumpe la edición ni exige completar campos.
            let autoSaveTimer = null;
            let admissionDirty = false;
            function persistAdmissionDraft() {
                if (!admissionDirty || !window.__FICHA_4TO?.editable) return;
                clearTimeout(autoSaveTimer);
                admissionDirty = false;
                try { localStorage.setItem(key, JSON.stringify(capture())); }
                catch (error) { admissionDirty = true; message.textContent = 'No se pudo guardar el borrador: ' + error.message; }
            }
            function scheduleAdmissionDraft() {
                if (!window.__FICHA_4TO?.editable) return;
                admissionDirty = true;
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(persistAdmissionDraft, 900);
            }
            form.addEventListener('input', scheduleAdmissionDraft);
            form.addEventListener('change', scheduleAdmissionDraft);
            tbody.addEventListener('click', e => { if (e.target.closest('.remove-med')) setTimeout(scheduleAdmissionDraft, 0); });
            document.getElementById('addMedication').addEventListener('click', scheduleAdmissionDraft);
            document.getElementById('allergyOther')?.addEventListener('change', function () {
                if (this.checked) {
                    const input = document.getElementById('allergyDetails');
                    input.placeholder = 'Especifica aquí el otro tipo de alergia';
                    input.focus();
                }
            });
            form.querySelectorAll('[name="reaccion_refiere"]').forEach(radio => radio.addEventListener('change', () => {
                const detail = document.getElementById('allergyReactionDetails');
                if (radio.checked && radio.value === 'no') {
                    detail.placeholder = 'No refiere reacción alérgica';
                    detail.value = '';
                    detail.disabled = true;
                } else if (radio.checked) {
                    detail.disabled = false;
                    detail.placeholder = 'Ej.: urticaria, disnea, náuseas o fecha de la reacción';
                }
            }));
            document.addEventListener('click', async function (event) {
                const link = event.target.closest('a[href]');
                if (!link || !link.closest('.tabs, .sidebar') || !admissionDirty || !window.__FICHA_4TO?.editable) return;
                if (event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.target === '_blank') return;
                const href = link.href;
                if (!href || new URL(href).origin !== location.origin) return;
                event.preventDefault();
                persistAdmissionDraft();
                try { await window.Ficha4toBridge.flush(); location.assign(href); }
                catch (error) { message.textContent = 'No fue posible guardar el borrador. Reintenta antes de salir: ' + error.message; }
            }, true);

            // CARGAR DATOS PREVIOS
            try {
                const saved = localStorage.getItem(key);

                if (saved) {
                    restore(JSON.parse(saved));
                    const noReaction = form.querySelector('[name="reaccion_refiere"][value="no"]');
                    if (noReaction?.checked) document.getElementById('allergyReactionDetails').disabled = true;
                }
            } catch (error) {
                // Si los datos no pueden recuperarse, se inicia vacío.
            }
        })();
    </script>
<script src="{{ asset('js/estudiante/tiempo-restante.js') }}" defer></script>@include('shared.app-extras')
</body>
</html>
