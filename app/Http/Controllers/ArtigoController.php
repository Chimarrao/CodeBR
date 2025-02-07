<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\ArtigoIdioma;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Highlight\Highlighter;
use Illuminate\Support\Str;

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

        $artigo->texto_dark = $this->aplicarModoEscuro($artigo->texto);

        $artigo->texto = $this->processarCodigoComHighlight($artigo->texto);
        $artigo->texto_dark = $this->processarCodigoComHighlight($artigo->texto_dark);

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
            $artigo->{"imagem_menor"} = $artigo->{"imagem"};

            if (!isset($artigo->{"imagem"})) {
                $artigo->{"imagem"} =  '/' . $this->obterPrimeiraImagemComRegex($artigo->texto);
                $artigo->{"imagem_menor"} = $artigo->{"imagem"};
            } elseif (str_contains($artigo->{"imagem"}, 'CodeBR-img') && !str_ends_with($artigo->{"imagem"}, '.gif')) {
                $artigo->{"imagem_menor"} = str_replace('/images/', '/images/pequenas/', $artigo->{"imagem"});
            }

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
            $artigo->{"imagem_menor"} = $artigo->{"imagem"};

            foreach ($artigos as $chave => $artigo) {
                $artigo->{"imagem_menor"} = $artigo->{"imagem"};

                if (!isset($artigo->{"imagem"})) {
                    $artigo->{"imagem"} =  '/' . $this->obterPrimeiraImagemComRegex($artigo->texto);
                    $artigo->{"imagem_menor"} = $artigo->{"imagem"};
                } elseif (str_contains($artigo->{"imagem"}, 'CodeBR-img') && !str_ends_with($artigo->{"imagem"}, '.gif')) {
                    $artigo->{"imagem_menor"} = str_replace('/images/', '/images/pequenas/', $artigo->{"imagem"});
                }

                $artigos[$chave] = $artigo;
            }

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
     * Adiciona alt e loading lazy
     *
     * @param string $nomeArtigo Nome do artigo (usado para gerar o slug no caminho das imagens).
     * @param string $conteudoHtml Conteúdo HTML do artigo.
     * @return string
     */
    private function processarImagensArtigo($nomeArtigo, $conteudoHtml)
    {
        $conteudoHtml = mb_convert_encoding($conteudoHtml, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($conteudoHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $imagens = $dom->getElementsByTagName('img');

        $slug = substr(preg_replace('/[^a-z0-9]+/i', '-', strtolower($nomeArtigo)), 0, 50);

        $primeiraImagem = true;

        $baseName = pathinfo($nomeArtigo, PATHINFO_FILENAME);
        $alt = ucwords(str_replace(['-', '_'], ' ', $baseName)) . ' ' . uniqid();

        foreach ($imagens as $img) {
            if ($img->getAttribute('alt')) {
                continue;
            }

            $img->setAttribute('alt', $alt);

            if (!$primeiraImagem) {
                $img->setAttribute('loading', 'lazy');
            } else {
                $primeiraImagem = false;
            }

            $src = $img->getAttribute('src');
            if (is_file(public_path($src))) {
                $info = getimagesize(public_path($src));
                $larguraOriginal = $info[0];
                $alturaOriginal = $info[1];

                $img->setAttribute('width', $larguraOriginal);
                $img->setAttribute('height', $alturaOriginal);

                if (preg_match('/\.(jpg|jpeg|webp|png|svg)$/i', $src, $matches)) {
                    $formato = strtolower($matches[1]);

                    $hash = hash('crc32', $src);
                    $novoNome = "/media/{$slug}-{$hash}.{$formato}";

                    $img->setAttribute('src', $novoNome);
                }
            }
        }

        return $dom->saveHTML();
    }

    /**
     * Processa e destaca blocos de código usando Highlight.php.
     *
     * @param string $texto O conteúdo do artigo em HTML.
     * @return string O texto processado com destaque de código.
     */
    private function processarCodigoComHighlight(string $texto): string
    {
        $highlighter = new Highlighter();

        $pattern = '/<pre><code class="language-(\w+)">(.*?)<\/code><\/pre>/s';
        $callback = function ($matches) use ($highlighter) {
            $language = $matches[1];
            $code = html_entity_decode($matches[2]);
            $code = ltrim($code);
            $code = str_replace('?-->', '?>', $code);
            $code = str_replace('<!--?php', '<?php', $code);

            try {
                $result = $highlighter->highlight($language, $code);
                return <<<HTML
                    <div class="code-container">
                        <button class="copy-btn" onclick="copyCode(this)"><i class="fa-solid fa-copy"></i></button>
                        <pre><code class="hljs ' . $language . '">$result->value</code></pre>
                    </div>
                HTML;
            } catch (\Exception $e) {
                $code = htmlspecialchars($code);
                return <<<HTML
                    <div class="code-container">
                        <button class="copy-btn" onclick="copyCode(this)"><i class="fa-solid fa-copy"></i></button>
                        <pre><code class="hljs">$code</code></pre>
                    </div>
                HTML;
            }
        };

        return preg_replace_callback($pattern, $callback, $texto);
    }

    /**
     * Aplica o modo escuro em uma string HTML utilizando DOMDocument.
     *
     * @param  string $html Conteúdo HTML de entrada.
     * @return string       HTML transformado com as alterações de modo escuro.
     */
    private function aplicarModoEscuro(string $html)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//p | //span | //strong");

        foreach ($nodes as $node) {
            $temAncestorGist = $xpath->query("ancestor::*[contains(@class, 'gist')]", $node)->length > 0;
            $temAncestorPre  = $xpath->query("ancestor::pre", $node)->length > 0;
            $temAncestorCode = $xpath->query("ancestor::code", $node)->length > 0;

            if (!$temAncestorGist && !$temAncestorPre && !$temAncestorCode) {
                if ($node->hasAttribute('style')) {
                    $style = $node->getAttribute('style');

                    $newStyle = preg_replace('/\s*background-color\s*:\s*[^;]+;?\s*/i', '', $style);

                    if (empty(trim($newStyle))) {
                        $node->removeAttribute('style');
                    } else {
                        $node->setAttribute('style', $newStyle);
                    }
                }

                $estiloAtual = $node->getAttribute('style');

                if (strpos($estiloAtual, ' color:') === false) {
                    $node->setAttribute('style', trim($estiloAtual . ' color: white;'));
                } else {
                    $node->setAttribute('style', preg_replace('/color:\s?[^;]+;?/i', 'color: white;', $estiloAtual));
                }
            }
        }

        $nodesLi = $xpath->query("//li");

        foreach ($nodesLi as $node) {
            $estiloAtual = $node->getAttribute('style');
            if (strpos($estiloAtual, 'color:') === false) {
                $node->setAttribute('style', trim($estiloAtual . ' color: white;'));
            } else {
                $node->setAttribute('style', preg_replace('/color:\s?[^;]+;?/i', 'color: white;', $estiloAtual));
            }
        }

        $blackStyledElements = $xpath->query('//*[contains(@style, "color: black") or contains(@style, "color: #000000") and not(contains(@class, "hljs"))]');
        foreach ($blackStyledElements as $element) {
            $element->setAttribute('style', 'color: white;');
        }

        return $dom->saveHTML();
    }

    /**
     * Inseri um artigo traduzido no banco
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inserirArtigoTraduzido(Request $request) {
        $id_artigo = $request->input('id_artigo');
        $json = $request->input('json');

        $artigoAntigo = Artigo::where('id_artigo', $id_artigo)->first();
        $artigoIdiomaAntigo = ArtigoIdioma::where('id_artigo', $id_artigo)->first();

        $json = str_replace("```json", "", $json);
        $json = str_replace("```", "", $json);
        $json = json_decode($json, true);

        $artigo = new Artigo();
        $artigo->artigo = isset($json['title']) ? $json['title'] : $json['titulo'];
        $artigo->liberado = 0;
        $artigo->destaque = 0;
        $artigo->descricao = isset($json['description']) ? $json['description'] : $json['descricao'];
        $artigo->html_title = isset($json['title']) ? $json['title'] : $json['titulo'];
        $artigo->html_meta = isset($json['description']) ? $json['description'] : $json['descricao'];
        $artigo->texto =  isset($json['text']) ? $json['text'] : $json['texto'];
        $artigo->imagem = $artigoAntigo->imagem;
        $artigo->url = $json['url'];
        $artigo->autor = $artigoAntigo->autor;
        $artigo->lang = $request->input('lang') ?? 'en-us';
        $artigo->tags = $json['tags'];
        $artigo->excluido = 0;
        $artigo->data_criacao = now();
        $artigo->data_modificacao = now();
        $artigo->data_publicacao = now();
        $artigo->save();

        $uuid = $artigoIdiomaAntigo ? $artigoIdiomaAntigo->id_ligacao : Str::uuid();

        $artigoIdioma = new ArtigoIdioma();
        $artigoIdioma->id_artigo = $artigo->id_artigo;
        $artigoIdioma->id_ligacao = $uuid;
        $artigoIdioma->save();

        if (!$artigoIdiomaAntigo) {
            $artigoIdioma = new ArtigoIdioma();
            $artigoIdioma->id_artigo = $id_artigo;
            $artigoIdioma->id_ligacao = $uuid;
            $artigoIdioma->save();
        }

        return response()->json([
            'response' => $json,
        ]);
    }
}
