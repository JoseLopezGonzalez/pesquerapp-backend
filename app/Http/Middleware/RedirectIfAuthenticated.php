<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si es una solicitud de API, devolvemos una respuesta JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Already authenticated.',
                    ], 200);
                }

                // Para solicitudes normales, redirigimos
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
