<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function get(Request $request)
    {
        $caminhoImagem = $request->getRequestUri();
        
        if (preg_match('/-([a-f0-9]{8})\.[a-z]+$/i', $caminhoImagem, $matches)) {
            $hashBuscado = $matches[1];
            $diretorioImagens = public_path('images/');
            $arquivosImagens = scandir($diretorioImagens);

            foreach ($arquivosImagens as $arquivo) {
                if (hash('crc32', '/images/' . $arquivo) === $hashBuscado) {
                    return response()->file(public_path('/images/' . $arquivo));
                }
            }
        }

        return null;
    }
}
