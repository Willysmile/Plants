<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepottingHistory extends Model
{
    protected $table = 'repotting_history';

    protected $fillable = [
        'plant_id',
        'repotting_date',
        'old_pot_size',
        'old_pot_unit',
        'new_pot_size',
        'new_pot_unit',
        'soil_type',
        'notes',
    ];

    protected $casts = [
        'repotting_date' => 'datetime',
    ];

    /**
     * Get the plant that owns this repotting record.
     */
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
