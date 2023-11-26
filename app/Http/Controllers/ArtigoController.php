<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\Comentario;
use Illuminate\Support\Facades\DB;

class ArtigoController extends Controller
{
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

    private function getComentarios(int $id_artigo) {
        $comentarios = Comentario::where('id_artigo', $id_artigo)
            ->where('id_comentario_resposta', 0)
            ->get();
            
        $respostas = Comentario::where('id_artigo', $id_artigo)
            ->where('id_comentario_resposta', '>', 0)
            ->get();

        foreach ($comentarios as $chave => $comentario) {
            $comentario->{"respostas"} = array();

            foreach ($respostas as $resposta) {
                if ($comentario->id_comentario == $resposta->id_comentario_resposta) {
                    $comentario->respostas[] = $resposta;
                }
            }

            $comentarios[$chave] = $comentario;
        }

        return $comentarios;
    }

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

    private function attCaminhoImagem2($texto)
    {
        $caminhoLaravel = asset('/');
        $padrao = '/<img\s+src="\/public\/images\/([^"]+)"([^>]*)>/';
        $substituicao = '<img src="' . $caminhoLaravel . 'images/$1"$2>';
        $novoTexto = preg_replace($padrao, $substituicao, $texto);
        return $novoTexto;
    }
}
