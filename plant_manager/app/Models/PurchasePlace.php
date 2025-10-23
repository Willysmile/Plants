<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasePlace extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'website',
        'type',
    ];

    /**
     * Relation: Un lieu d'achat peut avoir plusieurs plantes
     */
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}
