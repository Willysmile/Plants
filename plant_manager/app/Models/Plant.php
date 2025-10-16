<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Plant extends Model
{
    use HasFactory;

    // Champs autorisés à l'assignation de masse
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

    /**
     * Labels pour la fréquence d'arrosage.
     */
    public static array $wateringLabels = [
        1 => 'Très rare',
        2 => 'Rare',
        3 => 'Moyen',
        4 => 'Fréquent',
        5 => 'Quotidien',
    ];

    /**
     * Icônes pour la fréquence d'arrosage.
     */
    public static array $wateringIcons = [
        1 => 'droplet-slash',
        2 => 'droplet',
        3 => 'droplets',
        4 => 'droplets',
        5 => 'waves',
    ];

    /**
     * Couleurs pour la fréquence d'arrosage.
     */
    public static array $wateringColors = [
        1 => 'gray',
        2 => 'blue',
        3 => 'cyan',
        4 => 'blue-600',
        5 => 'blue-700',
    ];

    /**
     * Labels pour les besoins en lumière.
     */
    public static array $lightLabels = [
        1 => 'Faible lumière',
        2 => 'Lumière modérée',
        3 => 'Lumière moyenne',
        4 => 'Bonne lumière',
        5 => 'Soleil direct',
    ];

    /**
     * Icônes pour les besoins en lumière.
     */
    public static array $lightIcons = [
        1 => 'moon',
        2 => 'cloud',
        3 => 'sun',
        4 => 'sun-bright',
        5 => 'sun-bright',
    ];

    /**
     * Couleurs pour les besoins en lumière.
     */
    public static array $lightColors = [
        1 => 'gray',
        2 => 'yellow-200',
        3 => 'yellow',
        4 => 'yellow-600',
        5 => 'orange-600',
    ];

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
