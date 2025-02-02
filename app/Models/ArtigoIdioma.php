<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtigoIdioma extends Model
{
    protected $table = 'artigos_idiomas';
    protected $fillable = ['id_ligacao', 'id_artigo'];
}
