
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar usuario | Ficha Clínica TP</title>
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
           class="nav-link active">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}"
           class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
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
                <strong>Editar usuario</strong>
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
                    <h1>Editar usuario</h1>
                    <p class="subtitle">
                        Actualiza la información y los permisos de la cuenta.
                    </p>
                </div>
                <a href="{{ route('preview.admin.usuarios') }}"
                   class="primary-btn">← Volver a usuarios</a>
            </div>

            <div class="create-layout">
                <section class="panel">
                    <form id="editUserForm">
                        <h2>Información personal</h2>
                        <p class="form-description">
                            Modifica los datos del usuario seleccionado.
                        </p>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="nombres">Nombres</label>
                                <input id="nombres" name="nombres"
                                       maxlength="100" required>
                            </div>
                            <div class="form-field">
                                <label for="apellidos">Apellidos</label>
                                <input id="apellidos" name="apellidos"
                                       maxlength="100" required>
                            </div>
                            <div class="form-field">
                                <label for="rut">RUT</label>
                                <input id="rut" name="rut"
                                       maxlength="12" required>
                            </div>
                            <div class="form-field">
                                <label for="correo">Correo electrónico</label>
                                <input type="email" id="correo"
                                       name="correo" required>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <h2>Acceso y perfil</h2>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="rol">Rol</label>
                                <select id="rol" name="rol">
                                    <option>Estudiante</option>
                                    <option>Docente</option>
                                    <option>Administrador</option>
                                </select>
                            </div>

                            <div class="form-field" id="levelField">
                                <label for="nivel">Nivel educativo</label>
                                <select id="nivel" name="nivel">
                                    <option>3.º medio</option>
                                    <option>4.º medio</option>
                                </select>
                            </div>

                            <div class="form-field" id="courseField">
                                <label for="curso">Curso</label>
                                <select id="curso" name="curso"></select>
                            </div>

                            <div class="form-field">
                                <label for="estado">Estado de la cuenta</label>
                                <select id="estado" name="estado">
                                    <option>Activo</option>
                                    <option>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div id="editMessage" class="form-message"
                             role="status" aria-live="polite"></div>

                        <div class="form-actions" style="margin-top:24px">
                            <a href="{{ route('preview.admin.usuarios') }}"
                               class="secondary-btn">Cancelar</a>
                            <button type="submit" class="primary-btn">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="panel account-summary">
                    <h2>Resumen de la cuenta</h2>
                    <p class="form-description">
                        Comprueba los cambios antes de guardarlos.
                    </p>

                    <div class="summary-highlight">
                        <span>Usuario seleccionado</span>
                        <strong id="summaryName">—</strong>
                    </div>

                    <div class="summary-row">
                        <span>Rol</span>
                        <strong id="summaryRole">—</strong>
                    </div>
                    <div class="summary-row">
                        <span>Nivel</span>
                        <strong id="summaryLevel">—</strong>
                    </div>
                    <div class="summary-row">
                        <span>Curso</span>
                        <strong id="summaryCourse">—</strong>
                    </div>
                    <div class="summary-row">
                        <span>Estado</span>
                        <strong id="summaryStatus">—</strong>
                    </div>

                    <div class="summary-note">
                        <strong>Consideraciones</strong>
                        <p>
                            Los cambios de rol y estado deben ser validados
                            por el backend antes de aplicarse a una cuenta real.
                        </p>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<script>
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

    // Datos ficticios de los cinco usuarios del listado.
    const users = {
        1: {
            nombres: 'Camila', apellidos: 'Torres',
            rut: '21.234.567-8', correo: 'camila.torres@liceobicentenariohsb.cl',
            rol: 'Estudiante', nivel: '4.º medio',
            curso: '4.º A', estado: 'Activo'
        },
        2: {
            nombres: 'Matías', apellidos: 'Rojas',
            rut: '22.345.678-9', correo: 'matias.rojas@liceobicentenariohsb.cl',
            rol: 'Estudiante', nivel: '3.º medio',
            curso: '3.º B', estado: 'Activo'
        },
        3: {
            nombres: 'Daniela', apellidos: 'Pérez',
            rut: '17.456.789-0', correo: 'daniela.perez@liceobicentenariohsb.cl',
            rol: 'Docente', nivel: '', curso: '', estado: 'Activo'
        },
        4: {
            nombres: 'Felipe', apellidos: 'Muñoz',
            rut: '23.567.890-1', correo: 'felipe.munoz@liceobicentenariohsb.cl',
            rol: 'Estudiante', nivel: '4.º medio',
            curso: '4.º B', estado: 'Inactivo'
        },
        5: {
            nombres: 'Andrea', apellidos: 'Silva',
            rut: '16.678.901-2', correo: 'andrea.silva@liceobicentenariohsb.cl',
            rol: 'Administrador', nivel: '', curso: '', estado: 'Activo'
        }
    };

    const id = new URLSearchParams(window.location.search).get('id');
    const user = Object.hasOwn(users, id) ? users[id] : null;

    const rol = document.getElementById('rol');
    const nivel = document.getElementById('nivel');
    const curso = document.getElementById('curso');
    const estado = document.getElementById('estado');

    const courses = {
        '3.º medio': ['3.º A', '3.º B'],
        '4.º medio': ['4.º A', '4.º B']
    };

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

    function updateForm() {
        const isStudent = rol.value === 'Estudiante';

        document.getElementById('levelField').hidden = !isStudent;
        document.getElementById('courseField').hidden = !isStudent;

        nivel.disabled = !isStudent;
        curso.disabled = !isStudent;

        const nombres = document.getElementById('nombres').value;
        const apellidos = document.getElementById('apellidos').value;

        document.getElementById('summaryName').textContent =
            `${nombres} ${apellidos}`.trim() || '—';

        document.getElementById('summaryRole').textContent = rol.value;
        document.getElementById('summaryLevel').textContent =
            isStudent ? nivel.value : 'No aplica';
        document.getElementById('summaryCourse').textContent =
            isStudent ? curso.value : 'No aplica';
        document.getElementById('summaryStatus').textContent = estado.value;
    }

    if (user) {
        ['nombres', 'apellidos', 'rut', 'correo'].forEach(field => {
            document.getElementById(field).value = user[field];
        });

        rol.value = user.rol;
        nivel.value = user.nivel || '4.º medio';
        populateCourses(user.curso);
        estado.value = user.estado;
        updateForm();
    } else {
        document.getElementById('editUserForm').hidden = true;
        document.getElementById('summaryName').textContent =
            'Usuario no encontrado';
    }

    nivel.addEventListener('change', () => {
        populateCourses();
        updateForm();
    });

    [rol, curso, estado,
     document.getElementById('nombres'),
     document.getElementById('apellidos')].forEach(element => {
        element.addEventListener('input', updateForm);
    });

    document.getElementById('editUserForm')
        .addEventListener('submit', event => {
            event.preventDefault();

            const message = document.getElementById('editMessage');
            message.textContent =
                'Cambios validados en la demostración. ' +
                'No se han guardado en la base de datos.';
            message.classList.add('visible');
        });
</script>
</body>
</html>
