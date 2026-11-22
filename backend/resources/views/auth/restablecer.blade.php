<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablecer contraseña | Ficha Clínica TP</title>
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<main><section class="login-card">
    <div class="login-header"><h1>Nueva contraseña</h1><p>Confirma tu nueva contraseña para recuperar el acceso.</p></div>
    @if ($errors->any()) <div class="message visible" role="alert">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="field"><label for="email">Correo de la cuenta</label>
            <input type="email" id="email" name="email" autocomplete="email" value="{{ old('email', $email) }}" required></div>
        <div class="field"><label for="password">Nueva contraseña (mínimo 8 caracteres)</label>
            <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required></div>
        <div class="field"><label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" minlength="8" required></div>
        <button type="submit" class="login-button">Guardar contraseña</button>
    </form>
    <p class="auth-back"><a href="{{ route('preview.login') }}">Volver al inicio de sesión</a></p>
</section></main>
<footer><div><div class="footer-name">Ficha Clínica TP</div><div class="footer-subtitle">Plataforma educativa de simulación clínica</div></div></footer>
</body>
</html>
