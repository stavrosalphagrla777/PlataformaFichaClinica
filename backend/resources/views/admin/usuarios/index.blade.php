
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de usuarios | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-tp.png') }}"
                    alt="Logotipo TP Enfermería">
            </div>
            <span>Ficha Clínica TP</span>
        </div>
        <div class="nav-title">Principal</div>
        <a href="{{ route('preview.admin.dashboard') }}" class="nav-link">Dashboard</a>
        <div class="nav-title">Acciones</div>
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link active">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
        <a href="{{ route('preview.admin.historial') }}" class="nav-link">Historial de actividad</a>
        <div class="nav-title">Funciones docentes</div>
            <a href="#" class="nav-link">Crear casos clínicos</a>
            <a href="#" class="nav-link">Asignación de casos</a>
            <a href="#" class="nav-link">Revisión de fichas</a>
            <a href="#" class="nav-link">Evaluaciones</a>
        <div class="nav-title">Configuración</div>
            <a href="#" class="nav-link">Configuración</a>
            <a href="#" class="nav-link">Cerrar sesión</a>
    </aside>

    <div class="main">
        <header class="topbar">

            <div class="topbar-left">

                <button
                    type="button"
                    class="menu-toggle"
                    id="menuToggle"
                    aria-label="Ocultar menú lateral"
                    aria-expanded="true"
                    aria-controls="sidebar"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <strong>Gestión de usuarios</strong>

            </div>

            <div class="user-info">

                <div>
                    <strong>Administrador</strong>
                    <small>Panel administrativo</small>
                </div>

                <div class="user-avatar">
                    <span>A</span>
                </div>

            </div>

        </header>
    

        <main class="content">
            <div class="heading">
                <div>
                    <h1>Usuarios de la plataforma</h1>
                    <p class="subtitle">Consulta, búsqueda y administración de cuentas educativas</p>
                </div>
                <a href="{{ route('preview.admin.crear') }}" class="primary-btn">
                    + Crear usuario
                </a>
            </div>

            <section class="stats">
                <div class="stat">
                    <span class="stat-label">Total de usuarios</span>
                    <span class="stat-value">154</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Estudiantes</span>
                    <span class="stat-value">145</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Docentes</span>
                    <span class="stat-value">7</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Administradores</span>
                    <span class="stat-value">2</span>
                </div>
            </section>

            <section class="panel">
                <h2>Listado de usuarios</h2>

                <div class="filters">
                    <input id="search" type="search"
                           placeholder="Buscar por nombre, correo o RUT..."
                           aria-label="Buscar usuarios">

                    <select id="role" aria-label="Filtrar por rol">
                        <option value="">Todos los roles</option>
                        <option>Estudiante</option>
                        <option>Docente</option>
                        <option>Administrador</option>
                    </select>

                    <select id="level" aria-label="Filtrar por nivel">
                        <option value="">Todos los niveles</option>
                        <option>3.º medio</option>
                        <option>4.º medio</option>
                    </select>

                    <select id="status" aria-label="Filtrar por estado">
                        <option value="">Todos los estados</option>
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>

                    <button type="button" class="clear-btn admin-clean-button" id="clearFilters">
                        Limpiar filtros
                    </button>
                </div>

                <div class="bulk-toolbar" id="bulkToolbar" aria-live="polite">
                    <strong id="selectionCount">0 usuarios seleccionados</strong>
                    <div class="bulk-buttons">
                        <button type="button" id="bulkActivate" disabled>Activar seleccionados</button>
                        <button type="button" id="bulkDeactivate" disabled>Desactivar seleccionados</button>
                        <button type="button" id="clearSelection" disabled>Deseleccionar</button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th class="check-col"><input type="checkbox" id="selectAll" aria-label="Seleccionar todos los usuarios visibles" title="Seleccionar usuarios visibles"></th>
                                <th>Nombre / correo</th>
                                <th>RUT</th>
                                <th>Rol</th>
                                <th>Nivel</th>
                                <th>Curso</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="userTable">
                            <tr data-role="Estudiante" data-level="4.º medio" data-status="Activo">
                                <td class="check-col"><input type="checkbox" class="user-check" aria-label="Seleccionar usuario"></td><td>Camila Torres<span class="email">camila.torres@liceobicentenariohsb.cl</span></td>
                                <td>21.234.567-8</td><td>Estudiante</td><td>4.º medio</td><td>4.º A</td>
                                <td><span class="status active-status">Activo</span></td>
                                <td><a href="{{ route('preview.admin.editar', ['id' => 1]) }}"
                                   class="action">Editar</a>
                                <button class="action danger" type="button">Desactivar</button></td>
                            </tr>
                            <tr data-role="Estudiante" data-level="3.º medio" data-status="Activo">
                                <td class="check-col"><input type="checkbox" class="user-check" aria-label="Seleccionar usuario"></td><td>Matías Rojas<span class="email">matias.rojas@liceobicentenariohsb.cl</span></td>
                                <td>22.345.678-9</td><td>Estudiante</td><td>3.º medio</td><td>3.º B</td>
                                <td><span class="status active-status">Activo</span></td>
                                <td><a href="{{ route('preview.admin.editar', ['id' => 2]) }}"
                                   class="action">Editar</a><button class="action danger" type="button">Desactivar</button></td>
                            </tr>
                            <tr data-role="Docente" data-level="" data-status="Activo">
                                <td class="check-col"><input type="checkbox" class="user-check" aria-label="Seleccionar usuario"></td><td>Daniela Pérez<span class="email">daniela.perez@liceobicentenariohsb.cl</span></td>
                                <td>17.456.789-0</td><td>Docente</td><td>—</td><td>—</td>
                                <td><span class="status active-status">Activo</span></td>
                                <td><a href="{{ route('preview.admin.editar', ['id' => 3]) }}"
                                   class="action">Editar</a><button class="action danger" type="button">Desactivar</button></td>
                            </tr>
                            <tr data-role="Estudiante" data-level="4.º medio" data-status="Inactivo">
                                <td class="check-col"><input type="checkbox" class="user-check" aria-label="Seleccionar usuario"></td><td>Felipe Muñoz<span class="email">felipe.munoz@liceobicentenariohsb.cl</span></td>
                                <td>23.567.890-1</td><td>Estudiante</td><td>4.º medio</td><td>4.º B</td>
                                <td><span class="status inactive-status">Inactivo</span></td>
                                <td><a href="{{ route('preview.admin.editar', ['id' => 4]) }}"
                                   class="action">Editar</a><button class="action" type="button">Reactivar</button></td>
                            </tr>
                            <tr data-role="Administrador" data-level="" data-status="Activo">
                                <td class="check-col"><input type="checkbox" class="user-check" aria-label="Seleccionar usuario"></td><td>Andrea Silva<span class="email">andrea.silva@liceobicentenariohsb.cl</span></td>
                                <td>16.678.901-2</td><td>Administrador</td><td>—</td><td>—</td>
                                <td><span class="status active-status">Activo</span></td>
                                <td><a href="{{ route('preview.admin.editar', ['id' => 5]) }}"
                                   class="action">Editar</a><button class="action danger" type="button">Desactivar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p id="resultCount" class="footnote" aria-live="polite">
                    Mostrando 5 usuarios de ejemplo.
                </p>
                <p class="footnote">
                    Datos ficticios para demostrar la interfaz. Las estadísticas son ilustrativas.
                </p>
            </section>
        </main>
    </div>
</div>
<!-- Ventana emergente de confirmación -->
<div class="modal-overlay" id="statusModal" hidden>
    <div class="modal-card"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modalTitle"
         aria-describedby="modalDescription">

        <div class="modal-icon" id="modalIcon">
            !
        </div>

        <h2 id="modalTitle">Confirmar acción</h2>

        <p id="modalDescription">
            ¿Estás seguro de que deseas cambiar el estado de este usuario?
        </p>

        <div class="modal-actions">
            <button type="button"
                    class="modal-cancel"
                    id="cancelStatus">
                Cancelar
            </button>

            <button type="button"
                    class="modal-confirm"
                    id="confirmStatus">
                Confirmar
            </button>
        </div>
    </div>
</div>
<script>

    const search = document.getElementById('search');
    const role = document.getElementById('role');
    const level = document.getElementById('level');
    const status = document.getElementById('status');
    const rows = Array.from(document.querySelectorAll('#userTable tr'));
    const resultCount = document.getElementById('resultCount');

    function filterUsers() {
        const query = search.value.trim().toLocaleLowerCase('es');
        let visible = 0;

        rows.forEach(row => {
            const matchesSearch = row.textContent.toLocaleLowerCase('es').includes(query);
            const matchesRole = !role.value || row.dataset.role === role.value;
            const matchesLevel = !level.value || row.dataset.level === level.value;
            const matchesStatus = !status.value || row.dataset.status === status.value;

            const show = matchesSearch && matchesRole && matchesLevel && matchesStatus;
            row.hidden = !show;
            row.style.display = show ? '' : 'none';

            if (show) visible++;
        });

        resultCount.textContent = `Mostrando ${visible} de ${rows.length} usuarios de ejemplo.`;
        updateSelection();
    }

    [search, role, level, status].forEach(control => {
        control.addEventListener('input', filterUsers);
    });

    document.getElementById('clearFilters').addEventListener('click', () => {
        search.value = '';
        role.value = '';
        level.value = '';
        status.value = '';
        filterUsers();
    });


    // Modal personalizado para activar y desactivar usuarios

    const statusModal = document.getElementById('statusModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalIcon = document.getElementById('modalIcon');
    const confirmStatus = document.getElementById('confirmStatus');
    const cancelStatus = document.getElementById('cancelStatus');

    let bulkTargetStatus = null;
    let bulkTargetRows = [];
    const selectAll = document.getElementById('selectAll');
    const checkboxes = Array.from(document.querySelectorAll('.user-check'));
    const selectionCount = document.getElementById('selectionCount');
    const bulkActivate = document.getElementById('bulkActivate');
    const bulkDeactivate = document.getElementById('bulkDeactivate');
    const clearSelection = document.getElementById('clearSelection');

    function updateSelection() {
        const selected = checkboxes.filter(c => c.checked);
        const visible = checkboxes.filter(c => c.closest('tr').style.display !== 'none');
        selectionCount.textContent = `${selected.length} usuario${selected.length === 1 ? '' : 's'} seleccionado${selected.length === 1 ? '' : 's'}`;
        [bulkActivate, bulkDeactivate, clearSelection].forEach(b => b.disabled = !selected.length);
        selectAll.checked = visible.length > 0 && visible.every(c => c.checked);
        selectAll.indeterminate = visible.some(c => c.checked) && !selectAll.checked;
    }
    checkboxes.forEach(c => c.addEventListener('change', updateSelection));
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(c => { if (c.closest('tr').style.display !== 'none') c.checked = selectAll.checked; });
        updateSelection();
    });
    clearSelection.addEventListener('click', () => { checkboxes.forEach(c => c.checked = false); updateSelection(); });

    function applyStatus(row, newStatus) {
        row.dataset.status = newStatus;
        const badge = row.querySelector('.status');
        badge.textContent = newStatus;
        badge.className = newStatus === 'Activo' ? 'status active-status' : 'status inactive-status';
        const button = row.querySelector('button.action');
        button.textContent = newStatus === 'Activo' ? 'Desactivar' : 'Reactivar';
        button.classList.toggle('danger', newStatus === 'Activo');
    }

    function openBulkModal(targetStatus) {
        bulkTargetRows = checkboxes.filter(c => c.checked).map(c => c.closest('tr'));
        if (!bulkTargetRows.length) return;
        bulkTargetStatus = targetStatus;
        previousFocus = document.activeElement;
        const verb = targetStatus === 'Activo' ? 'reactivar' : 'desactivar';
        modalTitle.textContent = targetStatus === 'Activo' ? 'Activar usuarios seleccionados' : 'Desactivar usuarios seleccionados';
        modalDescription.textContent = `¿Deseas ${verb} las cuentas de los ${bulkTargetRows.length} usuarios seleccionados?`;
        modalIcon.textContent = targetStatus === 'Activo' ? '✓' : '!';
        modalIcon.className = targetStatus === 'Activo' ? 'modal-icon activate' : 'modal-icon deactivate';
        confirmStatus.textContent = targetStatus === 'Activo' ? 'Sí, activar' : 'Sí, desactivar';
        confirmStatus.className = targetStatus === 'Activo' ? 'modal-confirm activate' : 'modal-confirm deactivate';
        statusModal.hidden = false;
        document.body.style.overflow = 'hidden';
        cancelStatus.focus();
    }
    bulkActivate.addEventListener('click', () => openBulkModal('Activo'));
    bulkDeactivate.addEventListener('click', () => openBulkModal('Inactivo'));

    let selectedButton = null;
    let selectedRow = null;
    let previousFocus = null;

    function closeStatusModal() {
        statusModal.hidden = true;
        document.body.style.overflow = '';

        if (previousFocus) {
            previousFocus.focus();
        }

        selectedButton = null;
        selectedRow = null;
        bulkTargetStatus = null;
        bulkTargetRows = [];
    }

    document.querySelectorAll('#userTable button.action').forEach(button => {
        button.addEventListener('click', () => {
            selectedButton = button;
            selectedRow = button.closest('tr');
            previousFocus = document.activeElement;

            const isActive = selectedRow.dataset.status === 'Activo';
            const userName = selectedRow.querySelector('td').firstChild.textContent.trim();

            modalTitle.textContent = isActive
                ? 'Desactivar usuario'
                : 'Reactivar usuario';

            modalDescription.textContent = isActive
                ? `¿Estás seguro de que deseas desactivar la cuenta de ${userName}?`
                : `¿Deseas reactivar la cuenta de ${userName}?`;

            modalIcon.textContent = isActive ? '!' : '✓';
            modalIcon.className = isActive
                ? 'modal-icon deactivate'
                : 'modal-icon activate';

            confirmStatus.textContent = isActive
                ? 'Sí, desactivar'
                : 'Sí, reactivar';

            confirmStatus.className = isActive
                ? 'modal-confirm deactivate'
                : 'modal-confirm activate';

            statusModal.hidden = false;
            document.body.style.overflow = 'hidden';
            cancelStatus.focus();
        });
    });

    // Confirmar cambios individuales o masivos (solo vista previa).
    confirmStatus.addEventListener('click', () => {
        if (bulkTargetStatus) {
            bulkTargetRows.forEach(row => applyStatus(row, bulkTargetStatus));
            checkboxes.forEach(c => c.checked = false);
        } else if (selectedRow) {
            applyStatus(selectedRow, selectedRow.dataset.status === 'Activo' ? 'Inactivo' : 'Activo');
        } else return;
        closeStatusModal();
        filterUsers();
        updateSelection();
    });

    // Cancelar
    cancelStatus.addEventListener('click', closeStatusModal);

    // Cerrar haciendo clic fuera de la ventana
    statusModal.addEventListener('click', event => {
        if (event.target === statusModal) {
            closeStatusModal();
        }
    });

    // Cerrar con Escape y mantener el foco dentro del modal
    document.addEventListener('keydown', event => {
        if (statusModal.hidden) return;

        if (event.key === 'Escape') {
            closeStatusModal();
        }

        if (event.key === 'Tab') {
            const focusable = [cancelStatus, confirmStatus];
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
    // Mostrar y ocultar menú lateral
    const menuToggle = document.getElementById('menuToggle');
    const app = document.querySelector('.app');

    menuToggle.addEventListener('click', () => {
        const hidden = app.classList.toggle('sidebar-hidden');

        menuToggle.setAttribute('aria-expanded', String(!hidden));
        menuToggle.setAttribute(
            'aria-label',
            hidden ? 'Mostrar menú lateral' : 'Ocultar menú lateral'
        );
    });
</script>
</body>
</html>
