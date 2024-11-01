<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CaptureZoneResource;
use App\Models\CaptureZone;
use Illuminate\Http\Request;

class CaptureZoneController extends Controller
{
    /* index */
    public function index()
    {
        $captureZones = CaptureZone::all();
        return CaptureZoneResource::collection($captureZones);
    }
}
