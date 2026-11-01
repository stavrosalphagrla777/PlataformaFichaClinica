
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud recibida | Ficha Clínica</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main>
        <section class="login-card">
            <div class="login-header">
                <div class="success-symbol">✓</div>
                <h1>Revisa tu correo</h1>
                <p>
                    Si existe una cuenta asociada al correo ingresado,
                    recibirás un enlace para restablecer tu contraseña.
                </p>
            </div>

            <a href="{{ route('preview.login') }}"
               class="login-button button-link">
                Volver a iniciar sesión
            </a>
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
