
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña | Ficha Clínica</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main>
        <section class="login-card">
            <div class="login-header">
                <h1>Recuperar contraseña</h1>
                <p>Ingresa tu correo electrónico para solicitar
                   un enlace de recuperación.</p>
            </div>

            <form action="{{ route('preview.confirmacion') }}" method="GET">
                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="ejemplo@liceobicentenariohsb.cl"
                        autocomplete="email"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Enviar enlace
                </button>
            </form>

            <p class="auth-back">
                <a href="{{ route('preview.login') }}">
                    ← Volver al inicio de sesión
                </a>
            </p>
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
</body>
</html>
