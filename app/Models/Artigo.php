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
}
