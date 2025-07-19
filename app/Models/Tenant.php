<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    
    protected $fillable = [
        'subdomain',
        'database',
        'active',
    ];
}
