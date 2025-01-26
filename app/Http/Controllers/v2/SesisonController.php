<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class SessionController extends Controller
{
    /**
     * Listar todas las sesiones abiertas.
     */
    public function index(Request $request)
    {
        // Filtrar y paginar sesiones
        $query = SanctumPersonalAccessToken::with('tokenable') // Relación con el modelo User
            ->orderBy('last_used_at', 'desc'); // Ordenar por último uso

        // Filtros opcionales
        if ($request->has('user_id')) {
            $query->where('tokenable_id', $request->input('user_id'));
        }
        if ($request->has('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->input('ip_address') . '%');
        }
        if ($request->has('platform')) {
            $query->where('platform', 'like', '%' . $request->input('platform') . '%');
        }
        if ($request->has('browser')) {
            $query->where('browser', 'like', '%' . $request->input('browser') . '%');
        }

        // Paginación
        $perPage = $request->input('per_page', 10);
        $sessions = $query->paginate($perPage);

        return response()->json($sessions);
    }
}
