
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña | Ficha Clínica</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main>
        <section class="login-card">
            <div class="login-header">
                <h1>Nueva contraseña</h1>
                <p>Ingresa y confirma tu nueva contraseña.</p>
            </div>

            <div class="message" id="passwordMessage" role="alert"></div>

            <form id="resetForm">
                <div class="field">
                    <label for="password">Contraseña nueva</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="password-input"
                            placeholder="Nueva contraseña"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >
                        <button type="button" class="toggle-password"
                                data-target="password">
                            Mostrar
                        </button>
                    </div>
                </div>

                <div class="field">
                    <label for="password_confirmation">
                        Confirmar nueva contraseña
                    </label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="password-input"
                            placeholder="Repite tu contraseña"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >
                        <button type="button" class="toggle-password"
                                data-target="password_confirmation">
                            Mostrar
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-button">
                    Continuar
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
        <div class="footer-logo">
            <img src="{{ asset('images/logo-tp.png') }}"
                 alt="Logotipo TP Enfermería" class="logo-img">
        </div>
    </footer>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const visible = input.type === 'text';

                input.type = visible ? 'password' : 'text';
                button.textContent = visible ? 'Mostrar' : 'Ocultar';
                button.setAttribute('aria-pressed', String(!visible));
            });
        });

        document.getElementById('resetForm').addEventListener('submit', event => {
            event.preventDefault();

            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const message = document.getElementById('passwordMessage');

            message.classList.add('visible');

            if (password.value !== confirmation.value) {
                message.textContent = 'Las contraseñas no coinciden.';
                password.classList.add('input-error');
                confirmation.classList.add('input-error');
                return;
            }

            password.classList.remove('input-error');
            confirmation.classList.remove('input-error');
            message.textContent =
                'Demostración: las contraseñas coinciden. El cambio real requiere un enlace válido y conexión al backend.';
        });
    </script>
</body>
</html>
