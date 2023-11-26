<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Artigo;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    public function post(Request $request)
    {
        if (!app()->environment('local')) {
            $validator = Validator::make($request->all(), [
                'g-recaptcha-response' => 'required|recaptcha',
            ]);

            if ($validator->fails()) {
                return response()->json(['erro' => 'Falha na validação do reCAPTCHA'], 422);
            }
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'sometimes',
            'comentario' => 'required|string',
            'url' => 'required|string',
        ]);

        $artigo = Artigo::where('url', $request->input('url'))->first();

        if (!$artigo) {
            return response()->json(['erro' => 'Artigo não encontrado'], 422);
        }

        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()], 422);
        }

        $comentario = new Comentario();
        $comentario->id_artigo = $artigo->id_artigo;
        $comentario->nome = (string) $request->input('nome');
        $comentario->email = (string) $request->input('email');
        $comentario->comentario = (string) $request->input('comentario');
        $comentario->data = now();
        $comentario->save();

        return response()->json(
            [
                'erro' => false,
                'nome' => $request->input('nome'),
                'comentario' => $request->input('comentario')
            ],
            200
        );
    }
}
