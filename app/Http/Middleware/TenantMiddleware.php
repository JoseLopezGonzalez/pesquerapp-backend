<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $request->header('X-Tenant');

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not specified'], 400);
        }

        $tenant = strtolower($tenant); // Por si viene en mayúsculas o con formato raro

        // Log para verificar que llega correctamente
        Log::info('🔐 Tenant detectado', ['tenant' => $tenant]);

        // Guardamos el tenant globalmente para usarlo en otras partes de la app
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
