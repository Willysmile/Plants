<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disease extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Relation: Une maladie a plusieurs historiques
     */
    public function diseaseHistories(): HasMany
    {
        return $this->hasMany(DiseaseHistory::class);
    }
}
