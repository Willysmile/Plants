<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FertilizingHistory extends Model
{
    protected $table = 'fertilizing_history';

    protected $fillable = [
        'plant_id',
        'fertilizing_date',
        'fertilizer_type',
        'amount',
        'notes',
    ];

    protected $casts = [
        'fertilizing_date' => 'datetime',
    ];

    /**
     * La plante associée à cette fertilisation.
     */
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
