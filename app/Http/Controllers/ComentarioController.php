<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function post()
    {
        return response()->json([
            'mensagem' => 'Rota de Comentários',
            'dados' => [
                'comentario1' => 'Este é o comentário 1',
                'comentario2' => 'Este é o comentário 2',
            ],
        ]);
    }
}
