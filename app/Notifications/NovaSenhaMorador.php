<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovaSenhaMorador extends Notification
{
    use Queueable;

    private $senha;
    private $nome;

    public function __construct($senha, $nome)
    {
        $this->senha = $senha;
        $this->nome = $nome;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Sua nova senha de acesso - ' . config('app.name'))
            ->greeting('Olá, ' . $this->nome . '!')
            ->line('Uma nova senha de acesso foi gerada para você pelo administrador do condomínio.')
            ->line('Sua nova senha é: ' . $this->senha)
            ->action('Acessar o Sistema', url('/login'))
            ->line('Recomendamos que você altere esta senha após o seu primeiro acesso.')
            ->line('Se você não solicitou isso, entre em contato com a administração.');
    }
}