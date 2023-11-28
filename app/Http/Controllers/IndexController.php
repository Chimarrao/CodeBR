<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Exibe a página inicial com uma lista de artigos.
     *
     * @param  int  $numeroPagina  O número da página a ser exibida.
     * @return \Illuminate\View\View
     */
    public function index(int $numeroPagina = 1)
    {
        $artigosPorPagina = 9;
        $offset = ($numeroPagina - 1) * $artigosPorPagina;

        $query = DB::table('artigos')
            ->select('*')
            ->where('liberado', 1)
            ->where('excluido', 0)
            ->orderByDesc('id_artigo')
            ->offset($offset);

        $count = DB::table('artigos')->select('*')->where('liberado', 1)->where('excluido', 0);

        if (request()->has('q') && !empty(request('q'))) {
            $searchTerm = '%' . request('q') . '%';
            $query->where('artigo', 'like', $searchTerm);
            $count->where('artigo', 'like', $searchTerm);
        }

        $artigos = $query->limit($artigosPorPagina)->get()->toArray();
        $count = $count->count();

        $artigosDestaque = DB::table('artigos')->select('*')->where('liberado', 1)->where('excluido', 0)->where('destaque', 1)->get();

        foreach ($artigos as $chave => $artigo) {
            if (isset($artigo->{"imagem"})) {
                continue;
            }

            $artigo->{"imagem"} = $this->obterPrimeiraImagemComRegex($artigo->texto);
            $artigos[$chave] = $artigo;
        }

        foreach ($artigosDestaque as $chave => $artigo) {
            if (isset($artigo->{"imagem"})) {
                continue;
            }

            $artigo->{"imagem"} = $this->obterPrimeiraImagemComRegex($artigo->texto);
            $artigosDestaque[$chave] = $artigo;
        }

        return view('index', [
            'count' => $count,
            'artigos' => $artigos,
            'numeroPagina' => $numeroPagina,
            'artigosDestaque' => $artigosDestaque,
            'artigosPorPagina' => $artigosPorPagina,
        ]);
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
