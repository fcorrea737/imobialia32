<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Força o header 'Accept' para 'application/json' em todas as requisições que passam por aqui.
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
