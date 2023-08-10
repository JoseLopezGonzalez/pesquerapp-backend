<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptureZone extends Model
{
    use HasFactory;
    protected $table = 'capture_zones';

    protected $fillable = ['name'];

    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
