
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asignar roles y niveles | Ficha Clínica TP</title>
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
        <a href="#" class="nav-link">Dashboard</a>

        <div class="nav-title">Acciones</div>
        <a href="{{ route('preview.admin.usuarios') }}"
           class="nav-link">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}"
           class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}"
           class="nav-link active">Asignar roles y niveles</a>
        <a href="#" class="nav-link">Historial de actividad</a>

        <div class="nav-title">Configuración</div>
        <a href="#" class="nav-link">Configuración</a>
        <a href="#" class="nav-link">Cerrar sesión</a>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle"
                        id="menuToggle"
                        aria-label="Ocultar menú lateral"
                        aria-expanded="true"
                        aria-controls="sidebar">
                    <span></span><span></span><span></span>
                </button>
                <strong>Asignar roles y niveles</strong>
            </div>

            <div class="user-info">
                <div>
                    <strong>Administrador</strong>
                    <small>Panel administrativo</small>
                </div>
                <div class="user-avatar"><span>A</span></div>
            </div>
        </header>

        <main class="content">
            <div class="heading">
                <div>
                    <h1>Asignación de roles y niveles</h1>
                    <p class="subtitle">
                        Administra los perfiles de acceso de los usuarios
                        de la plataforma educativa.
                    </p>
                </div>

                <a href="{{ route('preview.admin.usuarios') }}"
                   class="primary-btn">← Volver a usuarios</a>
            </div>

            <div class="create-layout">
                <section class="panel">
                    <h2>Configuración de acceso</h2>
                    <p class="form-description">
                        Selecciona una cuenta y modifica sus permisos.
                    </p>

                    <form id="rolesForm">
                        <div class="form-field">
                            <label for="usuario">Seleccionar usuario</label>
                            <select id="usuario" required>
                                <option value="">Selecciona un usuario...</option>
                                <option value="1">Camila Torres — Estudiante</option>
                                <option value="2">Matías Rojas — Estudiante</option>
                                <option value="3">Daniela Pérez — Docente</option>
                                <option value="4">Felipe Muñoz — Estudiante</option>
                                <option value="5">Andrea Silva — Administrador</option>
                            </select>
                        </div>

                        <hr class="form-divider">

                        <h2>Rol y nivel educativo</h2>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="rol">Rol asignado</label>
                                <select id="rol" required disabled>
                                    <option>Estudiante</option>
                                    <option>Docente</option>
                                    <option>Administrador</option>
                                </select>
                            </div>

                            <div class="form-field" id="levelField">
                                <label for="nivel">Nivel educativo</label>
                                <select id="nivel" disabled>
                                    <option>3.º medio</option>
                                    <option>4.º medio</option>
                                </select>
                            </div>

                            <div class="form-field" id="courseField">
                                <label for="curso">Curso</label>
                                <select id="curso" disabled></select>
                            </div>
                        </div>

                        <p class="security-note">
                            Los cambios en los roles pueden modificar
                            las funcionalidades disponibles para cada usuario.
                            La autorización real debe validarse en el servidor.
                        </p>

                        <div class="form-actions">
                            <button type="button" class="secondary-btn"
                                    id="resetChanges" disabled>
                                Restablecer
                            </button>

                            <button type="submit" class="primary-btn"
                                    id="saveChanges" disabled>
                                Guardar asignación
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="panel account-summary">
                    <h2>Resumen del perfil</h2>
                    <p class="form-description">
                        Revisa la configuración antes de confirmar.
                    </p>

                    <div class="summary-highlight">
                        <span>Usuario seleccionado</span>
                        <strong id="summaryName">
                            Ningún usuario seleccionado
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Rol</span>
                        <strong id="summaryRole">—</strong>
                    </div>

                    <div class="summary-row">
                        <span>Nivel educativo</span>
                        <strong id="summaryLevel">—</strong>
                    </div>

                    <div class="summary-row">
                        <span>Curso</span>
                        <strong id="summaryCourse">—</strong>
                    </div>

                    <div class="summary-note">
                        <strong>Permisos del perfil</strong>
                        <p id="permissionsText">
                            Selecciona un usuario para consultar
                            los permisos correspondientes.
                        </p>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<!-- Ventana de confirmación -->
<div class="modal-overlay" id="rolesModal" hidden>
    <div class="modal-card"
         role="dialog"
         aria-modal="true"
         aria-labelledby="rolesModalTitle"
         aria-describedby="rolesModalDescription">

        <div class="modal-icon activate">✓</div>

        <h2 id="rolesModalTitle">Confirmar asignación</h2>

        <p id="rolesModalDescription">
            ¿Deseas aplicar esta configuración?
        </p>

        <div class="modal-actions">
            <button type="button" class="modal-cancel"
                    id="cancelRoles">
                Cancelar
            </button>

            <button type="button" class="modal-confirm"
                    id="confirmRoles">
                Confirmar cambios
            </button>
        </div>
    </div>
</div>

<script>
    // Menú lateral
    const app = document.querySelector('.app');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle.addEventListener('click', () => {
        const hidden = app.classList.toggle('sidebar-hidden');

        menuToggle.setAttribute('aria-expanded', String(!hidden));
        menuToggle.setAttribute(
            'aria-label',
            hidden ? 'Mostrar menú lateral' : 'Ocultar menú lateral'
        );
    });

    // Usuarios ficticios
    const users = {
        '1': {
            name: 'Camila Torres',
            role: 'Estudiante',
            level: '4.º medio',
            course: '4.º A'
        },
        '2': {
            name: 'Matías Rojas',
            role: 'Estudiante',
            level: '3.º medio',
            course: '3.º B'
        },
        '3': {
            name: 'Daniela Pérez',
            role: 'Docente',
            level: '',
            course: ''
        },
        '4': {
            name: 'Felipe Muñoz',
            role: 'Estudiante',
            level: '4.º medio',
            course: '4.º B'
        },
        '5': {
            name: 'Andrea Silva',
            role: 'Administrador',
            level: '',
            course: ''
        }
    };

    const courses = {
        '3.º medio': ['3.º A', '3.º B'],
        '4.º medio': ['4.º A', '4.º B']
    };

    const permissions = {
        'Estudiante':
            'Acceso a las funciones educativas correspondientes ' +
            'a su nivel de formación.',
        'Docente':
            'Acceso a las funciones docentes de seguimiento ' +
            'y evaluación, según los permisos definidos.',
        'Administrador':
            'Acceso a la administración de usuarios, roles ' +
            'y configuración autorizada de la plataforma.'
    };

    const usuario = document.getElementById('usuario');
    const rol = document.getElementById('rol');
    const nivel = document.getElementById('nivel');
    const curso = document.getElementById('curso');
    const saveChanges = document.getElementById('saveChanges');
    const resetChanges = document.getElementById('resetChanges');

    function populateCourses(selected = '') {
        curso.replaceChildren();

        (courses[nivel.value] || []).forEach(name => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            curso.appendChild(option);
        });

        if (selected) curso.value = selected;
    }

    function updateSummary() {
        const user = users[usuario.value];

        if (!user) return;

        const isStudent = rol.value === 'Estudiante';

        document.getElementById('levelField').hidden = !isStudent;
        document.getElementById('courseField').hidden = !isStudent;

        nivel.disabled = !isStudent;
        curso.disabled = !isStudent;

        document.getElementById('summaryName').textContent = user.name;
        document.getElementById('summaryRole').textContent = rol.value;
        document.getElementById('summaryLevel').textContent =
            isStudent ? nivel.value : 'No aplica';
        document.getElementById('summaryCourse').textContent =
            isStudent ? curso.value : 'No aplica';

        document.getElementById('permissionsText').textContent =
            permissions[rol.value];
    }

    function loadUser() {
        const user = users[usuario.value];
        const enabled = Boolean(user);

        rol.disabled = !enabled;
        saveChanges.disabled = !enabled;
        resetChanges.disabled = !enabled;

        if (!user) {
            rol.value = 'Estudiante';
            document.getElementById('levelField').hidden = true;
            document.getElementById('courseField').hidden = true;
            nivel.disabled = true;
            curso.disabled = true;

            document.getElementById('summaryName').textContent =
                'Ningún usuario seleccionado';

            ['summaryRole', 'summaryLevel', 'summaryCourse']
                .forEach(id => {
                    document.getElementById(id).textContent = '—';
                });

            document.getElementById('permissionsText').textContent =
                'Selecciona un usuario para consultar sus permisos.';
            return;
        }

        rol.value = user.role;
        nivel.value = user.level || '4.º medio';
        populateCourses(user.course);
        updateSummary();
    }

    usuario.addEventListener('change', loadUser);

    rol.addEventListener('change', updateSummary);

    nivel.addEventListener('change', () => {
        populateCourses();
        updateSummary();
    });

    curso.addEventListener('change', updateSummary);
    resetChanges.addEventListener('click', loadUser);

    loadUser();

    // Modal de confirmación
    const modal = document.getElementById('rolesModal');
    const cancelRoles = document.getElementById('cancelRoles');
    const confirmRoles = document.getElementById('confirmRoles');
    let previousFocus = null;

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        if (previousFocus) previousFocus.focus();
    }

    document.getElementById('rolesForm')
        .addEventListener('submit', event => {
            event.preventDefault();

            const user = users[usuario.value];
            if (!user) return;

            previousFocus = document.activeElement;

            document.getElementById('rolesModalDescription').textContent =
                `¿Deseas asignar el rol "${rol.value}" a ${user.name}? ` +
                'Esta operación es una demostración y no modifica la base de datos.';

            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            cancelRoles.focus();
        });

    cancelRoles.addEventListener('click', closeModal);

    confirmRoles.addEventListener('click', () => {
        // No modificamos los datos originales de demostración.
        closeModal();

        const notice = document.createElement('p');
        notice.className = 'form-message visible';
        notice.setAttribute('role', 'status');
        notice.textContent =
            'Asignación confirmada en la demostración. ' +
            'No se han guardado cambios reales.';

        const oldNotice = document.getElementById('rolesNotice');
        if (oldNotice) oldNotice.remove();

        notice.id = 'rolesNotice';
        document.querySelector('.form-actions').before(notice);
    });

    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', event => {
        if (modal.hidden) return;

        if (event.key === 'Escape') {
            closeModal();
        }

        if (event.key === 'Tab') {
            const first = cancelRoles;
            const last = confirmRoles;

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey &&
                       document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
</script>
</body>
</html>
