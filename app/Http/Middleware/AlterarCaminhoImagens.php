<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlterarCaminhoImagens
{
    /**
     * Manipula uma solicitação antes de passar para o próximo middleware na pilha.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $caminho = $request->getPathInfo();

        if (strpos($caminho, '/imagens') === 0) {
            $novoCaminho = str_replace('/imagens', '/images', $caminho);
            $request->server->set('REQUEST_URI', $novoCaminho);
        }

        return $next($request);
    }
}
