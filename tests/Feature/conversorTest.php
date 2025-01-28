<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Intervention\Image\Facades\Image;

class ConversorTest extends TestCase
{
    /**
     * Teste: Upload de uma imagem válida e conversão bem-sucedida.
     */
    public function testConverteImagemValida()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->image('imagem.jpeg')],
            'formatos' => ['png']
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'convertedFiles' => [['arquivo']]
        ]);
    }

    /**
     * Teste: Upload de vários arquivos com diferentes formatos de saída.
     */
    public function testConverteMultiplasImagens()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [
                UploadedFile::fake()->image('imagem1.jpeg'),
                UploadedFile::fake()->image('imagem2.png')
            ],
            'formatos' => ['gif', 'webp']
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'convertedFiles');
    }

    /**
     * Teste: Upload com formatos de arquivo inválidos.
     */
    public function testRejeitaFormatoInvalido()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->create('arquivo.pdf')],
            'formatos' => ['png']
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['arquivos.0']);
    }

    /**
     * Teste: Upload com formatos de saída inválidos.
     */
    public function testRejeitaFormatoDeSaidaInvalido()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->image('imagem.jpeg')],
            'formatos' => ['txt']
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['formatos.0']);
    }

    /**
     * Teste: Sem arquivos no upload.
     */
    public function testRejeitaSemArquivos()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [],
            'formatos' => ['png']
        ]);

        $response->assertStatus(422);
    }

    /**
     * Teste: Formato de saída não especificado.
     */
    public function testUsaFormatoPadrao()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->image('imagem.jpeg')]
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
    }

    /**
     * Teste: Upload de arquivo muito grande.
     */
    public function testRejeitaArquivoMuitoGrande()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->create('imagem.jpeg', 600 * 1024)],
            'formatos' => ['png']
        ]);

        $response->assertStatus(500);
    }

    /**
     * Teste: Caminho do arquivo convertido.
     */
    public function testCaminhoArquivoConvertido()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [UploadedFile::fake()->image('imagem.jpeg')],
            'formatos' => ['png']
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('convertedFiles.0.arquivo', fn($path) => str_starts_with($path, '/file/'));
    }

    /**
     * Teste: Upload com índice de formatos faltando.
     */
    public function testUsaFormatoPadraoParaFormatoFaltando()
    {
        $response = $this->postJson('/api/conversor-imagem', [
            'arquivos' => [
                UploadedFile::fake()->image('imagem1.jpeg'),
                UploadedFile::fake()->image('imagem2.png')
            ],
            'formatos' => ['png'] // Somente um formato especificado
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'convertedFiles');
    }
}
