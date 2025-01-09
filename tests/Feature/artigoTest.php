<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArtigoTest extends TestCase
{
    public function testArtigoSlug()
    {
        $slug = 'o-que-e-o-erro-404';

        $resposta = $this->get("/api/artigo?slug=$slug");

        $resposta->assertStatus(200);

        $resposta->assertJsonStructure([
            'success',
            'data' => [
                'artigo' => [
                    'id_artigo',
                    'artigo',
                    'liberado',
                    'destaque',
                    'descricao',
                    'html_title',
                    'html_meta',
                    'texto',
                    'imagem',
                    'url',
                    'autor',
                    'data_criacao',
                    'data_publicacao',
                    'data_modificacao',
                    'lang',
                    'tags',
                    'excluido'
                ]
            ]
        ]);

        $resposta->assertJson(['success' => true]);
    }

    public function testListaArtigos()
    {
        $resposta = $this->get('/api/artigos');

        $resposta->assertStatus(200);

        $resposta->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id_artigo',
                    'artigo',
                    'liberado',
                    'destaque',
                    'descricao',
                    'html_title',
                    'html_meta',
                    'texto',
                    'imagem',
                    'url',
                    'autor',
                    'data_criacao',
                    'data_publicacao',
                    'data_modificacao',
                    'lang',
                    'tags',
                    'excluido'
                ]
            ]
        ]);

        $resposta->assertJson(['success' => true]);
    }

    public function testArtigosDestaque()
    {
        $resposta = $this->get('/api/artigos-destaque');

        $resposta->assertStatus(200);

        $resposta->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id_artigo',
                    'artigo',
                    'liberado',
                    'destaque',
                    'descricao',
                    'html_title',
                    'html_meta',
                    'texto',
                    'imagem',
                    'url',
                    'autor',
                    'data_criacao',
                    'data_publicacao',
                    'data_modificacao',
                    'lang',
                    'tags',
                    'excluido'
                ]
            ]
        ]);

        $resposta->assertJson(['success' => true]);
    }

    /**
     * Testa se o código com Highlight é aplicado no php
     *
     * @return void
     */
    public function testProcessarCodigoComHighlight()
    {
        $slug = 'laravel-guia-completo-desenvolvimento-web-php';

        $response = $this->get("/api/artigo?slug={$slug}");
        $response->assertStatus(200)->assertJson(['success' => true]);

        $jsonData = $response->decodeResponseJson();
        
        $this->assertArrayHasKey('artigo', $jsonData['data']);
        $this->assertArrayHasKey('texto', $jsonData['data']['artigo']);

        $texto = $jsonData['data']['artigo']['texto'];
        $textoDark = $jsonData['data']['artigo']['texto_dark'];

        $this->assertStringContainsString('<div class="code-container">', $texto);
        $this->assertStringContainsString('class="hljs \' . php . \'">', $texto); 
        $this->assertStringNotContainsString('<!--?php', $texto);

        $this->assertStringContainsString('<div class="code-container">', $textoDark);
        $this->assertStringContainsString('class="hljs \' . php . \'">', $textoDark); 
        $this->assertStringNotContainsString('<!--?php', $textoDark);
    }

    /**
     * Testa se o modo escuro foi aplicado a um artigo específico.
     *
     * @return void
     */
    public function testArtigoModoEscuro()
    {
        $slug = 'property-hooks-php-8-4-novidades';

        $response = $this->get("/api/artigo?slug={$slug}");
        $response->assertStatus(200)->assertJson(['success' => true]);

        $jsonData = $response->decodeResponseJson();
        
        $this->assertArrayHasKey('artigo', $jsonData['data']);
        $this->assertArrayHasKey('texto', $jsonData['data']['artigo']);
        $this->assertArrayHasKey('texto_dark', $jsonData['data']['artigo']);

        $textoDark = $jsonData['data']['artigo']['texto_dark'];

        $this->assertStringNotContainsString('color: black;', $textoDark, 'texto_dark ainda contém estilo "color: black;"');

        $this->assertStringContainsString('color: white;', $textoDark, 'texto_dark não contém "color: white;" onde esperado');
    }

    /**
     * Testa se o modo escuro foi aplicado corretamente
     * e respeita exceções (blocos de código, gist, etc.).
     *
     * @return void
     */
    public function testArtigoModoEscuroRespeitandoBlocosDeCodigo()
    {
        $slug = 'property-hooks-php-8-4-novidades'; 

        $response = $this->get("/api/artigo?slug={$slug}");
        $response->assertStatus(200)->assertJson(['success' => true]);

        $jsonData = $response->decodeResponseJson();
        $this->assertArrayHasKey('artigo', $jsonData['data']);
        $this->assertArrayHasKey('texto_dark', $jsonData['data']['artigo']);

        $textoDark = $jsonData['data']['artigo']['texto_dark'];

        $this->assertStringNotContainsString('color: black;', $textoDark, 'texto_dark ainda contém estilo "color: black;"');

        $this->assertStringNotContainsString('color: white;" class="gist', $textoDark, 'Blocos .gist foram alterados, o que não era esperado.');

        $this->assertStringContainsString('color: white;', $textoDark, 'texto_dark não contém "color: white;" onde esperado');
    }
}
