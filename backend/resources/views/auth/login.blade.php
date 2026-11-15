
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

            @if (session('status'))
                <div class="message visible" role="status">{{ session('status') }}</div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('delivery.login.submit') }}">
                @csrf
                <div class="field">
                    <label for="login">Usuario o correo electrónico</label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        placeholder="admin o ejemplo@liceo.cl"
                        autocomplete="username"
                        value="{{ old('login', old('email')) }}"
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
                            class="password-input {{ $errors->has('login') || $errors->has('email') || $errors->has('password') ? 'input-error' : '' }}"
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

    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const loginForm = document.getElementById('loginForm');
        const loginMessage = document.getElementById('loginMessage');

        toggleButton.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';

            passwordInput.type = isVisible ? 'password' : 'text';
            toggleButton.textContent = isVisible ? 'Mostrar' : 'Ocultar';
            toggleButton.setAttribute('aria-pressed', String(!isVisible));
            toggleButton.setAttribute(
                'aria-label',
                isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña'
            );
        });


    </script>
</body>
</html>
