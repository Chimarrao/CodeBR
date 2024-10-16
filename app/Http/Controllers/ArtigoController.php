<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\Comentario;
use Illuminate\Http\Request;

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

    public function get(Request $request)
    {
        $artigosPorPagina = 9;

        $pagina = $request->input('page', 1);
        $query = $request->input('q', '');

        $artigosQuery = Artigo::where('artigo', 'like', "%{$query}%")
            ->where('liberado', 1)
            ->where('excluido', 0)
            ->orderByDesc('id_artigo');

        $totalArtigos = $artigosQuery->count();

        $artigos = $artigosQuery->skip(($pagina - 1) * $artigosPorPagina)
            ->take($artigosPorPagina)
            ->get();

        foreach ($artigos as $chave => $artigo) {
            if (isset($artigo->{"imagem"})) {
                $artigo->{"imagem"} = '/' . $artigo->{"imagem"};
                $artigos[$chave] = $artigo;
                continue;
            }

            $artigo->{"imagem"} = '/' . $this->obterPrimeiraImagemComRegex($artigo->texto);
            $artigos[$chave] = $artigo;
        }

        return response()->json([
            'success' => true,
            'data' => $artigos,
            'total' => $totalArtigos,
            'pagina_atual' => $pagina,
            'total_paginas' => ceil($totalArtigos / $artigosPorPagina),
        ]);
    }

    public function destaque()
    {
        $artigos = Artigo::where('destaque', 1)->get();

        foreach ($artigos as $chave => $artigo) {
            if (isset($artigo->{"imagem"})) {
                $artigo->{"imagem"} = '/' . $artigo->{"imagem"};
                $artigos[$chave] = $artigo;
                continue;
            }

            $artigo->{"imagem"} = '/' . $this->obterPrimeiraImagemComRegex($artigo->texto);
            $artigos[$chave] = $artigo;
        }

        return response()->json([
            'success' => true,
            'data' => $artigos
        ]);
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

    /**
     * Obtém a primeira imagem de um bloco de texto usando expressões regulares.
     *
     * @param  string  $html  O bloco de texto HTML.
     * @return string|null  O caminho da imagem ou null se não for encontrado.
     */
    private function obterPrimeiraImagemComRegex($html)
    {
        $padrao = '/<img [^>]*src=["\'](.*?\.webp)["\'][^>]*>/i';

        if (preg_match($padrao, $html, $matches)) {
            $imagem = $matches[1];
            $imagem = 'images/' . array_reverse(explode('/', $imagem))[0];

            return $imagem;
        }

        return null;
    }
}
