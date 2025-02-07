<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Artigo extends Model
{
    protected $table = 'artigos';
    protected $primaryKey = 'id_artigo';
    public $timestamps = false;

    protected $fillable = [
        'artigo',
        'liberado',
        'destaque',
        'descricao',
        'html_title',
        'html_meta',
        'texto',
        'imagem',
        'url',
        'autor',
        'data_criacao',
        'data_publicacao',
        'data_modificacao',
        'lang',
        'tags',
        'excluido',
    ];

    protected static function booted()
    {
        static::saving(function ($artigo) {
            $artigo->imagem = ltrim($artigo->imagem, 'images/');

            if (!empty($artigo->texto)) {
                $artigo->texto = static::processarImagensArtigo($artigo->artigo, $artigo->texto);
            }
        });
    }

    /**
     * Processa as imagens do conteúdo HTML, adiciona atributos e faz o upload para o GitHub.
     * 
     * @param string $nomeArtigo Nome do artigo
     * @param string $conteudoHtml HTML do artigo
     * @return string
     */
    private static function processarImagensArtigo(string $nomeArtigo, string $conteudoHtml)
    {
        $conteudoHtml = mb_convert_encoding($conteudoHtml, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($conteudoHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $imagens = $dom->getElementsByTagName('img');
        $slug = substr(preg_replace('/[^a-z0-9]+/i', '-', strtolower($nomeArtigo)), 0, 50);

        $primeiraImagem = true;

        foreach ($imagens as $img) {
            $src = $img->getAttribute('src');
            $alt = ucwords(str_replace(['-', '_'], ' ', pathinfo($nomeArtigo, PATHINFO_FILENAME))) . ' ' . uniqid();
            $img->setAttribute('alt', $alt);

            if (!$primeiraImagem) {
                $img->setAttribute('loading', 'lazy');
            } else {
                $primeiraImagem = false;
            }

            // Processa a imagem somente se for válida
            if (is_file(public_path($src))) {
                $info = getimagesize(public_path($src));
                $larguraOriginal = $info[0];
                $alturaOriginal = $info[1];
                $img->setAttribute('width', $larguraOriginal);
                $img->setAttribute('height', $alturaOriginal);

                if (preg_match('/\.(jpg|jpeg|webp|png|svg)$/i', $src, $matches)) {
                    $formato = strtolower($matches[1]);
                    $hash = hash('crc32', $src);
                    $novoNome = "{$slug}-{$hash}.{$formato}";

                    // Faz o upload para o GitHub
                    if (static::uploadImagemParaGithub($src, $novoNome)) {
                        // Atualiza o atributo `src` da imagem
                        $novoSrc = 'https://cdn.statically.io/gh/Chimarrao/CodeBR-img/img/images/internas/' . $novoNome;
                        $img->setAttribute('src', $novoSrc);
                    }

                }
            }
        }

        return $dom->saveHTML();
    }

    /**
     * Faz o upload da imagem para o GitHub.
     * @param string $src SRC da imagem
     * @param string $novoNome Novo nome da imagem
     * @return void
     */
    private static function uploadImagemParaGithub(string $src, string $novoNome)
    {
        $caminhoImagem = public_path($src);
        $githubToken = env('GITHUB_TOKEN');
        $nomeDoDonoGithub = 'Chimarrao';
        $nomeDoRepositorio = 'CodeBR-img';
        $branch = 'img';

        if (file_exists($caminhoImagem)) {
            $imageContent = base64_encode(file_get_contents($caminhoImagem));
            $nomeImagem = basename($novoNome);

            $apiUrl = "https://api.github.com/repos/{$nomeDoDonoGithub}/{$nomeDoRepositorio}/contents/images/internas/{$novoNome}";

            $response = Http::withHeaders([
                'Authorization' => "token {$githubToken}",
                'Accept' => 'application/vnd.github.v3+json',
            ])->put($apiUrl, [
                'message' => "Upload da imagem interna {$nomeImagem}",
                'content' => $imageContent,
                'branch' => $branch,
            ]);

            if ($response->failed()) {
                Log::error('Falha ao enviar a imagem para o GitHub: ' . $response->body());
                return false;
            }

            return true;
        }

        return false;
    }
}
