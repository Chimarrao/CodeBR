<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtigoCodigo extends Model
{
    protected $table = 'artigo_codigos';
    protected $primaryKey = 'id_artigo_codigo';
    public $timestamps = false;

    protected $fillable = [
        'id_artigo',
        'codigo',
        'language',
    ];
}
