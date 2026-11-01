
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear usuario | Ficha Clínica TP</title>
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
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link">
            Gestión de usuarios
        </a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link active">
            Crear usuario
        </a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
        <a href="#" class="nav-link">Historial de actividad</a>

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

                <strong>Crear usuario</strong>
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
                    <h1>Nuevo usuario de la plataforma</h1>
                    <p class="subtitle">
                        Registra una cuenta y asigna su perfil de acceso
                        al sistema educativo.
                    </p>
                </div>
                <a class="primary-btn" href="{{ route('preview.admin.usuarios') }}">
                    ← Volver a usuarios
                </a>
            </div>

            <div class="create-layout">
                <section class="panel">
                    <form id="createUserForm">
                        <h2>Información personal</h2>
                        <p class="form-description">
                            Completa los datos de identificación y contacto.
                        </p>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="nombres">Nombres</label>
                                <input id="nombres" name="nombres"
                                       placeholder="Ej.: Camila Fernanda"
                                       maxlength="100" required>
                            </div>

                            <div class="form-field">
                                <label for="apellidos">Apellidos</label>
                                <input id="apellidos" name="apellidos"
                                       placeholder="Ej.: Muñoz Pérez"
                                       maxlength="100" required>
                            </div>

                            <div class="form-field">
                                <label for="rut">RUT</label>
                                <input id="rut" name="rut"
                                       placeholder="Ej.: 12.345.678-9"
                                       maxlength="12" required>
                            </div>

                            <div class="form-field">
                                <label for="correo">Correo electrónico</label>
                                <input id="correo" name="correo" type="email"
                                       placeholder="usuario@liceobicentenariohsb.cl"
                                       required>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <h2>Acceso y perfil</h2>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="rol">Rol</label>
                                <select id="rol" name="rol" required>
                                    <option value="Estudiante">Estudiante</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>

                            <div class="form-field" id="levelField">
                                <label for="nivel">Nivel educativo</label>
                                <select id="nivel" name="nivel">
                                    <option value="3.º medio">3.º medio</option>
                                    <option value="4.º medio" selected>4.º medio</option>
                                </select>
                            </div>

                            <div class="form-field" id="courseField">
                                <label for="curso">Curso</label>
                                <select id="curso" name="curso">
                                    <option value="4.º medio A">4.º medio A</option>
                                    <option value="4.º medio B">4.º medio B</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="estado">Estado de la cuenta</label>
                                <select id="estado" name="estado">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <p class="security-note">
                            La contraseña inicial y su entrega deben seguir
                            la política de seguridad definida.
                        </p>

                        <div id="formMessage" class="form-message"
                             role="status" aria-live="polite"></div>

                        <div class="form-actions">
                            <a href="{{ route('preview.admin.usuarios') }}"
                               class="secondary-btn">Cancelar</a>
                            <button type="submit" class="primary-btn">
                                Crear usuario
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="panel account-summary">
                    <h2>Resumen de la cuenta</h2>
                    <p class="form-description">
                        Revisa los permisos antes de crearla.
                    </p>

                    <div class="summary-highlight">
                        <span>Rol seleccionado</span>
                        <strong id="summaryRole">Estudiante</strong>
                    </div>

                    <div class="summary-row">
                        <span>Nivel</span>
                        <strong id="summaryLevel">4.º medio</strong>
                    </div>
                    <div class="summary-row">
                        <span>Curso</span>
                        <strong id="summaryCourse">4.º medio A</strong>
                    </div>
                    <div class="summary-row">
                        <span>Estado</span>
                        <strong id="summaryStatus">Activo</strong>
                    </div>

                    <div class="summary-note">
                        <strong>Consideraciones</strong>
                        <p>
                            El nivel y el curso corresponden al perfil
                            estudiantil. Para otros roles, estos campos
                            pueden no aplicar.
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

    const rol = document.getElementById('rol');
    const nivel = document.getElementById('nivel');
    const curso = document.getElementById('curso');
    const estado = document.getElementById('estado');

    const courses = {
        '3.º medio': ['3.º medio A', '3.º medio B'],
        '4.º medio': ['4.º medio A', '4.º medio B']
    };

    function updateForm() {
        const isStudent = rol.value === 'Estudiante';

        document.getElementById('levelField').hidden = !isStudent;
        document.getElementById('courseField').hidden = !isStudent;

        nivel.disabled = !isStudent;
        curso.disabled = !isStudent;

        document.getElementById('summaryRole').textContent = rol.value;
        document.getElementById('summaryLevel').textContent =
            isStudent ? nivel.value : 'No aplica';
        document.getElementById('summaryCourse').textContent =
            isStudent ? curso.value : 'No aplica';
        document.getElementById('summaryStatus').textContent = estado.value;
    }

    nivel.addEventListener('change', () => {
        curso.replaceChildren();

        courses[nivel.value].forEach(name => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            curso.appendChild(option);
        });

        updateForm();
    });

    [rol, curso, estado].forEach(element => {
        element.addEventListener('change', updateForm);
    });

    updateForm();

    document.getElementById('createUserForm')
        .addEventListener('submit', event => {
            event.preventDefault();

            const message = document.getElementById('formMessage');
            message.textContent =
                'Formulario validado visualmente. La creación real del usuario ' +
                'se habilitará al conectar el backend.';
            message.classList.add('visible');
        });
</script>
</body>
</html>
