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

}
