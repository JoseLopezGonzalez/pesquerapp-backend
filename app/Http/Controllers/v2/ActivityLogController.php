<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Obtener los registros de actividad desde la base de datos
        $activityLogs = ActivityLog::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $activityLogs,
        ]);
    }
}
