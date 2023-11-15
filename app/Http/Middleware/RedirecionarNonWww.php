<?php

namespace App\Http\Middleware;

use Closure;

class RedirecionarNonWww
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
        $host = $request->getHost();

        if (strpos($host, 'www.') === 0) {
            $novoHost = substr($host, 4);

            return redirect()->to($request->scheme() . '://' . $novoHost . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
