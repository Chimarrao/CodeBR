<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

ini_set('upload_max_filesize', '512M');
ini_set('post_max_size', '512M');
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
ini_set('max_input_time', '300');

class ImageConverterController extends Controller
{
    /**
     * Converte imagens enviadas para os formatos solicitados.
     *
     * @param Request $request Objeto da requisição HTTP contendo os arquivos e formatos desejados.
     * @return \Illuminate\Http\JsonResponse Resposta com o status da conversão e arquivos convertidos.
     */
    public function converter(Request $request)
    {
        $request->validate([
            'arquivos.*' => 'required|file|mimes:jpeg,png,webp,gif,bmp',
            'formatos.*' => 'required|string|in:jpeg,png,webp,gif,bmp',
        ]);

        $arquivos = $request->file('arquivos');
        $formatos = $request->input('formatos');
        $arquivosConvertidos = [];

        try {
            foreach ($arquivos as $indice => $arquivo) {
                $formatoOriginal = $arquivo->getClientOriginalExtension();
                $formatoAlvo = $formatos[$indice] ?? 'jpeg';

                $imagem = $this->carregarImagem($arquivo->getRealPath(), $formatoOriginal);
                if (!$imagem) {
                    throw new \Exception("Formato de imagem não suportado: $formatoOriginal");
                }

                $nomeArquivoSaida = $this->sanitizarNomeArquivo($arquivo->getClientOriginalName(), $formatoAlvo);
                $caminhoArquivoSaida = 'converted/' . $nomeArquivoSaida;

                $this->salvarImagem($imagem, $formatoAlvo, $caminhoArquivoSaida);

                unset($imagem);

                $arquivosConvertidos[] = [
                    'arquivo' => '/file/' . $nomeArquivoSaida,
                ];
            }

            return response()->json([
                'success' => true,
                'convertedFiles' => $arquivosConvertidos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro durante a conversão: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sanitiza o nome do arquivo para evitar caracteres inválidos.
     *
     * @param string $nomeArquivo Nome original do arquivo.
     * @param string $extensao Extensão do formato alvo.
     * @return string Nome do arquivo sanitizado.
     */
    private function sanitizarNomeArquivo($nomeArquivo, $extensao)
    {
        $nomeSanitizado = preg_replace('/[^A-Za-z0-9\-_]/', '_', pathinfo($nomeArquivo, PATHINFO_FILENAME));
        return $nomeSanitizado . '-' . uniqid() . '.' . $extensao;
    }

    /**
     * Carrega a imagem utilizando a biblioteca Intervention Image.
     *
     * @param string $caminhoArquivo Caminho físico do arquivo.
     * @param string $formato Formato original da imagem.
     * @return \Intervention\Image\Image Instância da imagem carregada.
     */
    private function carregarImagem($caminhoArquivo, $formato)
    {
        return Image::make($caminhoArquivo);
    }

    /**
     * Salva a imagem no formato solicitado.
     *
     * @param \Intervention\Image\Image $imagem Instância da imagem a ser salva.
     * @param string $formato Formato alvo para salvar a imagem.
     * @param string $caminhoSaida Caminho onde a imagem será salva.
     * @throws \Exception Caso o formato não seja suportado.
     */
    private function salvarImagem($imagem, $formato, $caminhoSaida)
    {
        $formatosValidos = ['jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (!in_array(strtolower($formato), $formatosValidos)) {
            throw new \Exception("Formato alvo não suportado: $formato");
        }

        $dados = (string) $imagem->encode($formato);
        Storage::disk('public')->put($caminhoSaida, $dados);
    }
}
