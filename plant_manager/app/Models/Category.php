<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description', // si tu as ce champ
    ];

    /**
     * Relation avec les plantes.
     */
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }
}