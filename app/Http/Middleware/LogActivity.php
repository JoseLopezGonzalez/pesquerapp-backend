<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Jenssegers\Agent\Agent; // Necesitas instalar esta librería

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Obtener información del usuario
        $user = auth()->user();

        // Obtener información del dispositivo
        $agent = new Agent();
        $device = $agent->device();
        $browser = $agent->browser();
        $ipAddress = $request->ip();
        $action = $request->path();

        // Guardar el log
        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'ip_address' => $ipAddress,
            'device' => $device,
            'browser' => $browser,
            'details' => json_encode($request->all()), // Puedes ajustar esto según lo que quieras registrar
        ]);

        return $response;
    }
}
