<?php

namespace App\Http\Middleware;

use Closure;

class RedirecionarHttps
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
        if (app()->environment('local') || app()->environment('testing')) {
            return $next($request);
        }

        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
