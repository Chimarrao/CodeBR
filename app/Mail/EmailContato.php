<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailContato extends Mailable
{
    use Queueable, SerializesModels;

    public $nome;
    public $telefone;
    public $email;
    public $mensagem;

    /**
     * Create a new message instance.
     *
     * @param  string  $nome
     * @param  string  $telefone
     * @param  string  $email
     * @param  string  $mensagem
     * @return void
     */
    public function __construct($nome, $telefone, $email, $mensagem)
    {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->mensagem = $mensagem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contato')
            ->subject('Assunto do E-mail')
            ->with([
                'nome' => $this->nome,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'mensagem' => $this->mensagem,
            ]);
    }
}
