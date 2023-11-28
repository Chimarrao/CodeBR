<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\Comentario;
use Illuminate\Support\Facades\DB;

class ArtigoController extends Controller
{
    /**
     * Exibe um artigo com base no slug.
     *
     * @param  string  $slug  O slug do artigo.
     * @return \Illuminate\View\View
     */
    public function artigo(string $slug)
    {
        $artigo = Artigo::where('url', $slug)->first();

        if (!$artigo) {
            abort(404);
        }

        $comentarios = $this->getComentarios($artigo->id_artigo);
        $artigo->texto = $this->attCaminhoImagem($artigo->texto);
        $artigo->texto = $this->attCaminhoImagem2($artigo->texto);

        return view(
            'artigo',
            [
                'artigo' => $artigo,
                'comentarios' => $comentarios,
                'artigosSugeridos' => []
            ]
        );
    }

    /**
     * Obtém os comentários para um artigo.
     *
     * @param  int  $id_artigo  O ID do artigo.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getComentarios(int $id_artigo)
    {
        $comentarios = Comentario::where('id_artigo', $id_artigo)
            ->where('id_comentario_resposta', 0)
            ->orderBy('id', 'desc')
            ->get();

        $respostas = Comentario::where('id_artigo', $id_artigo)
            ->where('id_comentario_resposta', '>', 0)
            ->get();

        $colecaoRespostas = collect($respostas)->groupBy('id_comentario_resposta');

        foreach ($comentarios as $comentario) {
            if ($colecaoRespostas->has($comentario->id)) {
                $comentario->respostas = $colecaoRespostas->get($comentario->id)->all();
            } else {
                $comentario->respostas = [];
            }
        }

        return $comentarios;
    }

    /**
     * Atualiza o caminho das imagens no texto para usar a função 'asset'.
     *
     * @param  string  $texto  O texto contendo imagens.
     * @return string
     */
    private function attCaminhoImagem($texto)
    {
        $padrao = '/<img\s+[^>]*src=["\'](https:\/\/codebr\.net\/images\/[^"\']+)["\'][^>]*>/i';

        $callback = function ($matches) {
            $novoCaminho = asset('images/' . basename($matches[1]));
            return str_replace($matches[1], $novoCaminho, $matches[0]);
        };

        $novoTexto = preg_replace_callback($padrao, $callback, $texto);

        return $novoTexto;
    }

    /**
     * Atualiza o caminho das imagens no texto para usar a função 'asset' - Versão 2.
     *
     * @param  string  $texto  O texto contendo imagens.
     * @return string
     */
    private function attCaminhoImagem2($texto)
    {
        $caminhoLaravel = asset('/');
        $padrao = '/<img\s+src="\/public\/images\/([^"]+)"([^>]*)>/';
        $substituicao = '<img src="' . $caminhoLaravel . 'images/$1"$2>';
        $novoTexto = preg_replace($padrao, $substituicao, $texto);
        return $novoTexto;
    }
}
