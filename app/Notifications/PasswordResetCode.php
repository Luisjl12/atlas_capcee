<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetCode extends Notification
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail']; // Solo enviamos por correo
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu código de verificación')
            ->line('Hemos recibido una solicitud para restablecer tu contraseña.')
            ->line('Tu código de verificación es:')
            ->line("**{$this->code}**")
            ->line('Este código expirará en 10 minutos.')
            ->line('Si no solicitaste este cambio, ignora este mensaje.');
    }
}
