
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Ficha Clínica</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <main>
        <section class="login-card">

            <div class="login-header">
                <h1>Iniciar sesión</h1>
                <p>Plataforma Digital de Ficha Clínica</p>
            </div>

            <div
                class="message {{ $errors->any() ? 'visible' : '' }}"
                id="loginMessage"
                role="alert"
            >
                @if ($errors->any())
                    {{ $errors->first() }}
                @endif
            </div>

            <form id="loginForm" method="POST">
                @csrf
                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="ejemplo@liceobicentenariohsb.cl"
                        autocomplete="username"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>

                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="password-input {{ $errors->has('email') || $errors->has('password') ? 'input-error' : '' }}"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Mostrar contraseña"
                            aria-pressed="false"
                        >
                            Mostrar
                        </button>
                    </div>
                </div>

                <a href="{{ route('preview.recuperar') }}" class="forgot">
                    ¿Olvidaste tu contraseña?
                </a>

                <button type="submit" class="login-button">
                    Iniciar sesión
                </button>
            </form>
        </section>
    </main>

    <footer>
        <div>
            <div class="footer-name">Ficha Clínica TP</div>
            <div class="footer-subtitle">
                Plataforma educativa de simulación clínica
            </div>
        </div>

        <!-- Reemplazar por el logotipo institucional oficial -->
        <div class="footer-logo">
            <img
                src="{{ asset('images/logo-tp.png') }}"
                alt="Logotipo TP Enfermería"
                class="logo-img"
            >
        </div>
    </footer>

        <!-- Script para el login con Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const submitButton = loginForm.querySelector('button[type="submit"]');
            const loginMessage = document.getElementById('loginMessage');

            // Mostrar/Ocultar contraseña
            if (togglePasswordButton) {
                togglePasswordButton.addEventListener('click', function () {
                    const isVisible = passwordInput.type === 'text';
                    passwordInput.type = isVisible ? 'password' : 'text';
                    this.textContent = isVisible ? 'Mostrar' : 'Ocultar';
                    this.setAttribute('aria-pressed', String(!isVisible));
                    this.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
                });
            }

            // Manejar el envío del formulario
            loginForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Limpiar errores previos
                loginMessage.textContent = '';
                loginMessage.classList.remove('visible');
                emailInput.classList.remove('input-error');
                passwordInput.classList.remove('input-error');

                // Deshabilitar el botón mientras carga
                submitButton.disabled = true;
                submitButton.textContent = 'Iniciando sesión...';

                try {
                    const response = await axios.post('/api/auth/login', {
                        email: emailInput.value,
                        password: passwordInput.value,
                    });

                    const data = response.data;

                    // Guardar token y usuario
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // Redirigir según rol
                    const rol = data.user.rol;
                    if (rol === 'administrador') {
                        window.location.href = '/admin/usuarios';
                    } else if (rol === 'docente') {
                        window.location.href = '/docente/dashboard';
                    } else {
                        window.location.href = '/estudiante/mis-casos';
                    }
                } catch (error) {
                    let errorMessage = 'Ocurrió un error inesperado. Intenta de nuevo.';
                    if (error.response && error.response.data) {
                        if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.response.data.errors) {
                            const firstError = Object.values(error.response.data.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        }
                    }

                    // Mostrar mensaje
                    loginMessage.textContent = errorMessage;
                    loginMessage.classList.add('visible');
                    emailInput.classList.add('input-error');
                    passwordInput.classList.add('input-error');

                    // Reactivar botón
                    submitButton.disabled = false;
                    submitButton.textContent = 'Iniciar sesión';
                }
            });
        });
    </script>
</body>
</html>
