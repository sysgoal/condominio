<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoasVindasMorador extends Notification
{
    use Queueable;

    protected $senhaTemporaria;

    public function __construct($senhaTemporaria)
    {
        $this->senhaTemporaria = $senhaTemporaria;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bem-vindo ao ' . config('app.name'))
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Seu cadastro no sistema do Condomínio foi realizado com sucesso.')
            ->line('Abaixo estão seus dados de acesso:')
            ->line('**E-mail:** ' . $notifiable->email)
            ->line('**Senha Temporária:** ' . $this->senhaTemporaria)
            ->action('Acessar o Sistema', url('/login'))
            ->line('Recomendamos que você altere sua senha no primeiro acesso.')
            ->line('Obrigado por utilizar nossa plataforma!');
    }
}