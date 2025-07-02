<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Verifica se o usuário autenticado possui a role “admin”.
     * Caso contrário, devolve 403 (Forbidden).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado ou não for admin, bloqueia.
        if (! Auth::check() || ! Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Caso passe na verificação, continua o fluxo.
        return $next($request);
    }
}
