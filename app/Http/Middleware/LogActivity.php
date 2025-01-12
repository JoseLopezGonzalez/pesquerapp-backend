<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Log;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Obtener IP del cliente
            $ip = $request->ip();

            // Obtener información de ubicación con manejo de excepciones
            $location = null;
            try {
                $location = Location::get($ip);
            } catch (\Exception $e) {
                Log::error("Error obteniendo la ubicación: " . $e->getMessage());
            }

            // Analizar el User-Agent
            $agent = new Agent();
            $userAgentHeader = $request->header('User-Agent');
            if ($userAgentHeader) {
                $agent->setUserAgent($userAgentHeader);
            }

            // Verificar si el usuario está autenticado antes de registrar la actividad
            if (auth()->check()) {
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
            }
        } catch (\Exception $e) {
            Log::error("Error en el middleware LogActivity: " . $e->getMessage());
        }

        return $response;
    }
}
