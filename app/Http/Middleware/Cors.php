<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Permitir cualquier dominio solo si no se requieren credenciales
        $allowedOrigins = [
            'http://localhost:3000', // Puerto de localhost para desarrollo
            'http://api.congeladosbrisamar.es', // Dominio específico en producción
            'https://nextjs.congeladosbrisamar.es', // Otro dominio permitido
        ];

        $origin = $request->headers->get('Origin');

        // Verificar si el origen de la solicitud está permitido
        if (in_array($origin, $allowedOrigins)) {
            $response->header("Access-Control-Allow-Origin", $origin);
        }

        // Permitir el envío de credenciales (cookies, etc.)
        $response->header("Access-Control-Allow-Credentials", "true");

        // Métodos permitidos
        $response->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");

        // Cabeceras permitidas en la solicitud
        $response->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");

        // Responder correctamente a las solicitudes OPTIONS (Preflight)
        if ($request->getMethod() == "OPTIONS") {
            return $response->setStatusCode(200);
        }

        return $response;
    }
}
