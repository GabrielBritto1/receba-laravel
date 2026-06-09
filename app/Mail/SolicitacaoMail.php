<?php

namespace App\Mail;

use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Solicitacao $solicitacao,
        public User $solicitante
    ) {}

    public function envelope(): Envelope
    {
        $tipo = $this->solicitacao->tipo === 'cesta' ? 'Cesta Básica' : 'Item';
        return new Envelope(
            subject: "Nova Solicitação de {$tipo} — RECeBa",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitacao',
        );
    }
}
