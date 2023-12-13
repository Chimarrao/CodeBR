<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use App\Mail\EmailContato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContatoController extends Controller
{
    public function post(Request $request) {
        if (!app()->environment('local')) {
            $validator = Validator::make($request->all(), [
                'g_recaptcha_response' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['erro' => 'Falha na validação do reCAPTCHA'], 422);
            }
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'sometimes',
            'telefone' => 'sometimes',
            'mesangem' => 'required|string',
        ]);

        Contato::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'mensagem' => $request->input('mensagem'),
        ]);

        Mail::to('seu@email.com')->send(new EmailContato(
            $request->input('nome'),
            $request->input('telefone'),
            $request->input('email'),
            $request->input('mensagem')
        ));

        return response()->json(['message' => 'Mensagem enviada com sucesso', 'erro' => False]);
    }
}
