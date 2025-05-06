<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'phone',
        'email',
        'address',
        'cebo_export_type',
        'a3erp_cebo_code',
        'facilcom_cebo_code',
    ];

}
