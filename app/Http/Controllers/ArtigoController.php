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

        return view('artigo', [
            'artigo' => $artigo
        ]);
    }

    /**
     * Obtém um artigo baseado no slug e retorna seus dados e comentários.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $slug = $request->input('slug', 1);
        $artigo = Artigo::where('url', $slug)->first();

        if (!$artigo) {
            return response()->json([
                'success' => false,
            ]);
        }

        $comentarios = $this->getComentarios($artigo->id_artigo);

        $artigo->texto = $this->attCaminhoImagem($artigo->texto);
        $artigo->texto = $this->attCaminhoImagem2($artigo->texto);

        $artigo->texto = $this->processarGistsNoArtigo($artigo->texto);
        $artigo->texto = $this->processarImagensArtigo($artigo->artigo, $artigo->texto);

        return response()->json([
            'success' => true,
            'data' => [
                'artigo' => $artigo,
                'comentarios' => $comentarios
            ],
        ]);
    }

    /**
     * Processa e substitui gists no conteúdo do artigo.
     *
     * @param  string  $html  O conteúdo HTML do artigo.
     * @return string  O HTML processado com os gists substituídos.
     */
    private function processarGistsNoArtigo(string $html)
    {
        $pattern = '/<script\s+src="https:\/\/gist\.github\.com\/.*?\.js"><\/script>/i';

        preg_match_all($pattern, $html, $matches);

        foreach ($matches[0] as $scriptTag) {
            preg_match('/src="(.*?)"/', $scriptTag, $urlMatch);
            if (isset($urlMatch[1])) {
                $gistUrl = $urlMatch[1];
                $gistUrl = str_replace('.js', '', $gistUrl);

                $gistContent = file_get_contents($gistUrl . '.json');

                if ($gistContent !== false) {
                    $gistContent = json_decode($gistContent, true);
                    $css = $gistContent['stylesheet'];
                    $novoItem = $gistContent['div'];

                    $novoItem .= "<link href='$css' rel='stylesheet'>";

                    $html = str_replace($scriptTag, $novoItem, $html);
                }
            }
        }

        return $html;
    }

    /**
     * Obtém todos os artigos com paginação e busca.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
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

    /**
     * Obtém todos os artigos em destaque.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDestaque()
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
     * @return string  O texto atualizado com os caminhos corretos.
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
     * @return string  O texto atualizado com os caminhos corretos.
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

    /**
     * Processa o conteúdo HTML de um artigo, extrai todas as imagens (jpg, jpeg, webp, png, svg),
     * e renomeia os caminhos das imagens baseados no slug do nome do artigo e no hash CRC32
     * do nome original da imagem. Retorna um array com os novos caminhos das imagens.
     *
     * @param string $nomeArtigo Nome do artigo (usado para gerar o slug no caminho das imagens).
     * @param string $conteudoHtml Conteúdo HTML do artigo.
     * @return string
     */
    private function processarImagensArtigo($nomeArtigo, $conteudoHtml)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($conteudoHtml);
        libxml_clear_errors();

        $imagens = $dom->getElementsByTagName('img');

        $slug = substr(preg_replace('/[^a-z0-9]+/i', '-', strtolower($nomeArtigo)), 0, 50);

        foreach ($imagens as $img) {
            $src = $img->getAttribute('src');
            if (is_file(public_path($src))) {
                if (preg_match('/\.(jpg|jpeg|webp|png|svg)$/i', $src, $matches)) {
                    $formato = strtolower($matches[1]);
    
                    $hash = hash('crc32', $src);
                    $novoNome = "/media/{$slug}-{$hash}.{$formato}";
    
                    $conteudoHtml = str_replace($src, $novoNome, $conteudoHtml);
                }
            }
        }

        return $conteudoHtml;
    }
}
