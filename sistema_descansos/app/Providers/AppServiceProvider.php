<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Personalizar el correo de restablecimiento de contraseña
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            
            // Construimos la URL con el token y el correo
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Restablecer Contraseña - Sistema UCO')
                ->greeting('¡Hola, ' . $notifiable->nombre_completo . '!')
                ->line('Recibes este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
                ->action('Restablecer Contraseña', $url)
                ->line('Este enlace de recuperación expirará en 60 minutos.')
                ->line('Si tú no realizaste esta solicitud, puedes ignorar este mensaje de forma segura.')
                ->salutation('Saludos cordiales, el equipo del Sistema UCO');
        });
    }
}