<?php

namespace App\Models;

use App\Traits\UsesTenantConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletState extends Model
{
    use UsesTenantConnection;
    use HasFactory;
    protected $table = 'pallet_states';

    protected $fillable = ['name'];
    
    public function toArrayAssoc()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}
