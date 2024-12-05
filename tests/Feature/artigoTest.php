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
}
