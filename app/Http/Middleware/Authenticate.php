<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Evitar redirección a login, devolviendo null siempre.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null; // Nunca redirige a login
    }

    /**
     * Devolver respuesta JSON para solicitudes no autenticadas.
     */
    protected function unauthenticated(Request $request, AuthenticationException $exception)
    {
        return response()->json([
            'error' => 'No autorizado',
            'message' => 'Token inválido o no proporcionado.',
        ], 401);
    }
}
