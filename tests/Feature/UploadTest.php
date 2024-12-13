<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Classe de teste para uploads de arquivos na API.
 */
class UploadTest extends TestCase
{
    /**
     * Cliente HTTP usado para realizar as requisições.
     * 
     * @var Client
     */
    protected $client;

    /**
     * URL base da API de upload.
     * 
     * @var string
     */
    protected $baseUri = 'https://codebr.net/api/upload';

    /**
     * Configuração inicial para os testes.
     * Inicializa o cliente HTTP.
     */
    protected function setUp(): void
    {
        $this->client = new Client();
    }
    
    /**
     * Testa o upload de um arquivo pequeno no formato TXT.
     * Verifica se o upload retorna o status HTTP 200.
     * 
     * @return void
     */
    public function testUploadArquivoPequenoTxt()
    {
        $caminhoArquivo = $this->criarArquivoTemporario(10 * 1024, 'txt');

        $resposta = $this->client->post($this->baseUri, [
            'verify' => false,
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($caminhoArquivo, 'rb'),
                    'filename' => basename($caminhoArquivo),
                ],
            ],
        ]);

        $this->assertEquals(200, $resposta->getStatusCode(), "Falha no upload do arquivo TXT");
        
        unlink($caminhoArquivo);
    }

    /**
     * Testa o upload de um arquivo médio no formato PDF.
     * Verifica se o upload retorna o status HTTP 200.
     * 
     * @return void
     */
    public function testUploadArquivoMedioPdf()
    {
        $caminhoArquivo = $this->criarArquivoTemporario(5 * 1024 * 1024, 'pdf');

        $resposta = $this->client->post($this->baseUri, [
            'verify' => false,
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($caminhoArquivo, 'rb'),
                    'filename' => basename($caminhoArquivo),
                ],
            ],
        ]);

        $this->assertEquals(200, $resposta->getStatusCode(), "Falha no upload do arquivo PDF");

        unlink($caminhoArquivo);
    }

    /**
     * Testa o upload de um arquivo grande no formato MP4.
     * Verifica se o upload retorna o status HTTP 200.
     * 
     * @return void
     */
    public function testUploadArquivoGrandeMp4()
    {
        $caminhoArquivo = $this->criarArquivoTemporario(50 * 1024 * 1024, 'mp4');

        $resposta = $this->client->post($this->baseUri, [
            'verify' => false,
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($caminhoArquivo, 'rb'),
                    'filename' => basename($caminhoArquivo),
                ],
            ],
        ]);

        $this->assertEquals(200, $resposta->getStatusCode(), "Falha no upload do arquivo MP4");

        unlink($caminhoArquivo);
    }

    /**
     * Cria um arquivo temporário com conteúdo repetitivo.
     * 
     * @param int $tamanhoEmBytes Tamanho do arquivo em bytes.
     * @param string $extensao Extensão do arquivo.
     * @return string Caminho do arquivo temporário criado.
     */
    protected function criarArquivoTemporario($tamanhoEmBytes, $extensao)
    {
        $arquivoTemporario = tempnam(sys_get_temp_dir(), 'arquivoTeste_');
        $novoNome = $arquivoTemporario . '.' . $extensao;
        rename($arquivoTemporario, $novoNome);

        $handle = fopen($novoNome, 'wb');
        fwrite($handle, str_repeat('A', $tamanhoEmBytes));
        fclose($handle);

        return $novoNome;
    }
}
