<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArquivoController extends Controller
{
    public function store(Request $request)
    {
        // Recebemos: nome, link, tamanho
        $nome = $request->input('nome');
        $link = $request->input('link');
        $tamanho = $request->input('tamanho');

        // Validações simples
        if (!$nome || !$link || !$tamanho) {
            return response()->json(['success' => false, 'message' => 'Dados incompletos'], 422);
        }

        // Salvar no banco
        DB::table('arquivos')->insert([
            'nome' => $nome,
            'link' => $link,
            'tamanho' => $tamanho,
            'data_criacao' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Arquivo salvo com sucesso', 'link' => $link]);
    }
}
