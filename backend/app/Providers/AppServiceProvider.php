<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // El enlace del correo apunta al FRONTEND (no a una ruta de Laravel).
        $urlFrontend = function ($notifiable, string $token): string {
            return rtrim(config('app.frontend_url'), '/')
                .'/restablecer-contrasena?token='.$token
                .'&email='.urlencode($notifiable->getEmailForPasswordReset());
        };

        ResetPassword::createUrlUsing($urlFrontend);

        ResetPassword::toMailUsing(function ($notifiable, string $token) use ($urlFrontend) {
            return (new MailMessage)
                ->subject('Restablecer contraseña - Ficha Clínica TP')
                ->greeting('Hola '.$notifiable->name)
                ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
                ->action('Restablecer contraseña', $urlFrontend($notifiable, $token))
                ->line('Este enlace expira en 60 minutos.')
                ->line('Si no solicitaste este cambio, puedes ignorar este correo.');
        });
    }
}
