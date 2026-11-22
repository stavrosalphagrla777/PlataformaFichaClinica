<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Aviso privado en la plataforma. No envía correo mientras SMTP no esté validado. */
class ClinicalAlert extends Notification {
    use Queueable;
    public function __construct(public string $title, public string $message, public string $link) {}
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array {
        // Guardar rutas relativas para que el aviso funcione en otros hosts/puertos.
        $parts=parse_url($this->link);
        $path=($parts['path'] ?? '/');
        $query=isset($parts['query']) ? '?'.$parts['query'] : '';
        return ['titulo'=>$this->title,'mensaje'=>$this->message,'url'=>$path.$query];
    }
}
