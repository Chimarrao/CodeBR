<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

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
            $nomeArtigo = $artigo->artigo;
            $nomeArtigo = 'imagem-' . $nomeArtigo;
            $nomeArtigo = str_replace(' ', '-', $nomeArtigo);
            $nomeArtigo = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeArtigo);

            if (strlen($nomeArtigo) > 55) {
                $nomeArtigo = substr($nomeArtigo, 0, 55);
            }

            $nomeImagem = $nomeArtigo . '.webp';
            $caminhoImagem = public_path('images/' . $nomeImagem);

            if (!File::exists($caminhoImagem)) {
                $files = File::files(public_path('images'));

                usort($files, function ($a, $b) {
                    return filemtime($b) - filemtime($a);
                });

                $ultimaImagem = reset($files);

                if ($ultimaImagem) {
                    $imagem = Image::make($ultimaImagem);
                    $imagem->encode('webp', 80)->save($caminhoImagem);
                } else {
                    $artigo->imagem = null;
                    return;
                }
            }

            $artigo->imagem = 'images/' . $nomeImagem;
        });
    }
}
