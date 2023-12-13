<?php

namespace Tests\Feature;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class ContatoTest extends TestCase
{
    /**
     * Teste com todos os campos
     * 
     * @return void
     */
    public function testComentario()
    {
        $nome = 'Teste ' . Uuid::uuid4()->toString();
        $email = 'email_' . Uuid::uuid4()->toString() . '@codebr.net';
        $telefone = '+5551999999999';
        $mensagem = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. ' . Uuid::uuid4()->toString();

        $response = $this->postJson('/api/contato', [
            'nome' => $nome,
            'email' => $email,
            'mensagem' => $mensagem,
            'telefone' => $telefone,
            'g_recaptcha_response' => 'recaptcha_valido',
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(200)->assertJson(['erro' => false]);

        $this->assertDatabaseHas('contatos', [
            'nome' => $nome,
            'email' => $email,
            'mensagem' => $mensagem,
        ]);
    }
}