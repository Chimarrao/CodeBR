<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

ini_set('upload_max_filesize', '10240M');
ini_set('post_max_size', '10240M');

class ArquivoController extends Controller
{
    public function store(Request $request)
    {
        $nome = $request->input('nome');
        $link = $request->input('link');
        $tamanho = $request->input('tamanho');

        if (!$nome || !$link || !$tamanho) {
            return response()->json(['success' => false, 'message' => 'Dados incompletos'], 422);
        }

        DB::table('arquivos')->insert([
            'nome' => $nome,
            'link' => $link,
            'tamanho' => $tamanho,
            'data_criacao' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Arquivo salvo com sucesso', 'link' => $link]);
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');

        $client = new Client();

        $response = $client->post('https://anonymfile.com/api/v1/upload', [
            'verify' => false,
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ]
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        Log::info($result);

        return response()->json($result);
    }
}
