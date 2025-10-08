<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
   
    use HasFactory;

    // Champs autorisés à l’assignation de masse
    protected $fillable = [
        'name',
        'scientific_name',
        'purchase_date',
        'purchase_place',
        'purchase_price',
        'category_id',
        'description',
        'watering_frequency',
        'last_watering_date',
        'light_requirement',
        'temperature_min',
        'temperature_max',
        'humidity_level',
        'soil_humidity',
        'soil_ideal_ph',
        'soil_type',
        'info_url',
        'main_photo',
        'location',
        'pot_size',
        'health_status',
        'last_fertilizing_date',
        'fertilizing_frequency',
        'last_repotting_date',
        'next_repotting_date',
        'growth_speed',
        'max_height',
        'is_toxic',
        'flowering_season',
        'difficulty_level',
        'is_indoor',
        'is_outdoor',
        'is_favorite',
        'is_archived',
        'archived_date',
        'archived_reason'
    ];
/**
 * Les tags associés à cette plante.
 */
public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Photos associées à la plante
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Plantes "filles" (daughters) issues de cette plante
     */
    public function daughters()
    {
        return $this->belongsToMany(
            Plant::class,
            'plant_propagations',
            'parent_id',
            'daughter_id'
        )->withPivot('method','propagation_date')->withTimestamps();
    }

    /**
     * Plantes "mères" (parents) dont cette plante est issue
     */
    public function parents()
    {
        return $this->belongsToMany(
            Plant::class,
            'plant_propagations',
            'daughter_id',
            'parent_id'
        )->withPivot('method','propagation_date')->withTimestamps();
    }
}
