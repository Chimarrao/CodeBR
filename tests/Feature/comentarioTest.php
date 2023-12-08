<?php

namespace Tests\Feature;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class ComentarioTest extends TestCase
{
    /**
     * Teste com todos os campos
     * 
     * @return void
     */
    public function testeComTodosCampos()
    {
        $nome = 'Teste ' . Uuid::uuid4()->toString();
        $email = 'email_' . Uuid::uuid4()->toString() . '@codebr.net';
        $comentario = 'Meu comentário ' . Uuid::uuid4()->toString();

        $response = $this->postJson('/api/comentarios', [
            'nome' => $nome,
            'email' => $email,
            'comentario' => $comentario,

            'url' => 'escrever-txt-php',
            'g_recaptcha_response' => 'recaptcha_valido',
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(200)->assertJson(['erro' => false]);

        $this->assertDatabaseHas('comentarios', [
            'nome' => $nome,
            'email' => $email,
            'comentario' => $comentario,
        ]);
    }

    /**
     * Teste sem recaptcha
     * 
     * @return void
     */
    public function testPostWithAllFields()
    {
        $nome = 'Teste ' . Uuid::uuid4()->toString();
        $email = 'email_' . Uuid::uuid4()->toString() . '@codebr.net';
        $comentario = 'Meu comentário ' . Uuid::uuid4()->toString();

        $response = $this->postJson('/api/comentarios', [
            'nome' => $nome,
            'email' => $email,
            'comentario' => $comentario,

            'url' => 'escrever-txt-php',
            'g_recaptcha_response' => '',
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(422)->assertJson(['erro' => 'Falha na validação do reCAPTCHA']);

        $this->assertDatabaseMissing('comentarios', [
            'nome' => $nome,
            'email' => $email,
            'comentario' => $comentario,
        ]);
    }
}
