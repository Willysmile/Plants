<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WateringHistory extends Model
{
    protected $table = 'watering_history';

    protected $fillable = [
        'plant_id',
        'watering_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'watering_date' => 'datetime',
    ];

    /**
     * La plante associée à cet arrosage.
     */
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
