<?php

namespace App\Models;

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
        static::saving(function ($table) {
            $nomeArtigo = $table->artigo;
            $nomeArtigo = 'imagem-' . $nomeArtigo;
            $nomeArtigo = str_replace(' ', '-', $nomeArtigo);
            $nomeArtigo = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeArtigo);

            if (strlen($nomeArtigo) > 55) {
                $nomeArtigo = substr($nomeArtigo, 0, 55);
            }

            $nomeImagem = $nomeArtigo . '.webp';

            $table->imagem = 'images/' . $nomeImagem;
        });
    }
}
