<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizerType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function fertilizingHistories()
    {
        return $this->hasMany(FertilizingHistory::class);
    }
}
