<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailForgotPasswordCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param mixed $user O usuário para o qual o e-mail será enviado.
     * @param string $code O código de recuperação de senha.
     * @param string $formattedDate A data formatada para inclusão no e-mail.
     * @param string $formattedTime A hora formatada para inclusão no e-mail.
     */
    public function __construct(public $user, public $code, public $formattedDate, public $formattedTime)
    {
        //
    }

    /**
     * Obtém o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperar de senha de acesso',
        );
    }

    /**
     * Obtém a definição de conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendEmailHtmlForgotPasswordCode',
            text: 'emails.sendEmailTextForgotPasswordCode',
        );
    }

    /**
     * Obtém os anexos da mensagem.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
