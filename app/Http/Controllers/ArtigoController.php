<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtigoController extends Controller
{
    public function artigo(string $slug)
    {
        $artigo = DB::table('artigos')->select('*')->where('url', $slug)->first();

        if (!$artigo) {
            abort(404);
        }

        $artigo->texto = $this->attCaminhoImagem($artigo->texto);
        $artigo->texto = $this->attCaminhoImagem2($artigo->texto);

        return view(
            'artigo',
            [
                'artigo' => $artigo,
                'artigosSugeridos' => []
            ]
        );
    }

    private function attCaminhoImagem($texto) {
        $padrao = '/<img\s+[^>]*src=["\'](https:\/\/codebr\.net\/images\/[^"\']+)["\'][^>]*>/i';

        $callback = function ($matches) {
            $novoCaminho = asset('images/' . basename($matches[1]));
            return str_replace($matches[1], $novoCaminho, $matches[0]);
        };

        $novoTexto = preg_replace_callback($padrao, $callback, $texto);

        return $novoTexto;
    }

    private function attCaminhoImagem2($texto) {
        $caminhoLaravel = asset('/');
        $padrao = '/<img\s+src="\/public\/images\/([^"]+)"([^>]*)>/';
        $substituicao = '<img src="' . $caminhoLaravel . 'images/$1"$2>';
        $novoTexto = preg_replace($padrao, $substituicao, $texto);
        return $novoTexto;
    }
}
