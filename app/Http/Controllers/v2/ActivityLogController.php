<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los registros de actividad desde la base de datos

        $query = ActivityLog::query();

        /* usersIds */
        if ($request->has('users')) {
            $query->whereIn('user_id', $request->users);
        }

        /* ipAddresses */
        if ($request->has('ipAddresses')) {
            $query->whereIn('ip_address', $request->ipAddresses);
        }

        /* countries */
        if ($request->has('countries')) {
            $query->whereIn('country', $request->countries);
        }

        /* citie like*/
        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        /* path like */
        if ($request->has('path')) {
            $query->where('path', 'like', '%' . $request->path . '%');
        }

        /* order by created_at */
        $query->orderBy('created_at', 'desc');

        $perPage = $request->input('per_page', 10);
        $activityLogs = $query->paginate($perPage);

        return ActivityLogResource::collection($activityLogs);
    }
}
