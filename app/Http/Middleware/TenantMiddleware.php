<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $request->header('X-Tenant');

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not specified'], 400);
        }

        // Guardamos el tenant en una variable global o singleton para usarlo después
        app()->instance('currentTenant', $tenant);

        return $next($request);
    }
}
