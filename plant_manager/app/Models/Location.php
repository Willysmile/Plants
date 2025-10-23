<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'room',
        'light_level',
        'humidity_level',
        'temperature',
    ];

    /**
     * Relation: Un emplacement peut avoir plusieurs plantes
     */
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}

