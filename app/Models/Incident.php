<?php

// app/Models/Incident.php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use UsesTenantConnection;
    use HasFactory;

    protected $fillable = [
        'order_id',
        'description',
        'status',
        'resolution_type',
        'resolution_notes',
        'resolved_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function toArrayAssoc(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'status' => $this->status,
            'resolutionType' => $this->resolution_type,
            'resolutionNotes' => $this->resolution_notes,
            'resolvedAt' => $this->resolved_at,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
