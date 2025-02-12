<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Evitar redirección a login, devolviendo null siempre.
     */
    protected function redirectTo($request): ?string
    {
        // Si la solicitud espera JSON (API), devolver un error JSON en lugar de redirigir
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'message' => 'No autenticado. Necesitas iniciar sesión para acceder a esta ruta.'
            ], 401));
        }

        // Para solicitudes normales (web), redirigir a la página de login como siempre
        return route('login');
    }
}
