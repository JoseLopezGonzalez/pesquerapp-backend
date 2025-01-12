<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Obtener IP del cliente
        $ip = $request->ip();

        // Obtener información de ubicación
        $location = Location::get($ip);

        // Analizar el User-Agent
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        // Registrar actividad
        ActivityLog::create([
            'user_id' => auth()->id(),
            'ip_address' => $ip,
            'country' => $location?->countryName ?? 'Desconocido',
            'city' => $location?->cityName ?? 'Desconocido',
            'region' => $location?->regionName ?? 'Desconocido',
            'platform' => $agent->platform() ?? 'Desconocido',
            'browser' => $agent->browser() ?? 'Desconocido',
            'device' => $agent->device() ?? 'Desconocido',
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        return $response;
    }
}
