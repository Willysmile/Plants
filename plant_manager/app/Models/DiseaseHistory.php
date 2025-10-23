<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiseaseHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plant_id',
        'disease_name',
        'description',
        'treatment',
        'detected_at',
        'treated_at',
        'status',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'treated_at' => 'datetime',
    ];

    /**
     * Relation avec la plante.
     */
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    /**
     * Récupérer le statut lisible.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'detected' => '🔴 Détectée',
            'treated' => '🟡 Traitée',
            'cured' => '🟢 Guérie',
            'recurring' => '🔄 Récurrente',
            default => $this->status,
        };
    }

    /**
     * Récupérer l'icône de la maladie.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'detected' => 'bg-red-50 border-red-200 text-red-700',
            'treated' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
            'cured' => 'bg-green-50 border-green-200 text-green-700',
            'recurring' => 'bg-orange-50 border-orange-200 text-orange-700',
            default => 'bg-gray-50 border-gray-200 text-gray-700',
        };
    }
}
