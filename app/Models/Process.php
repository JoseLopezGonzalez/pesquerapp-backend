<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use UsesTenantConnection;

    use HasFactory;

    protected $fillable = ['name', 'type'];

   
}
