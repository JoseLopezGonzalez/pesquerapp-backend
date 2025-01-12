<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'country',
        'city',
        'region',
        'platform',
        'browser',
        'device',
        'path',
        'method',
        'location', // Nuevo campo
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
