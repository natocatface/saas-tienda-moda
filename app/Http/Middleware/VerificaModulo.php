<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaModulo
{
    /**
     * Restringe el acceso a un módulo según el rol del usuario.
     * Uso en rutas: ->middleware('modulo:ventas')
     */
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        $user = $request->user();

        if (!$user || !$user->puede($modulo)) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
