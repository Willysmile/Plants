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
     * Convertir les dates en instances Carbon
     */
    protected $casts = [
        // purchase_date est maintenant un string (accepte "dd/mm/yyyy" ou "mm/yyyy")
        'last_watering_date' => 'datetime',
        'last_fertilizing_date' => 'datetime',
        'last_repotting_date' => 'datetime',
        'next_repotting_date' => 'datetime',
        'archived_date' => 'datetime',
    ];

    /**
     * Accessor: Formate la date d'achat pour l'affichage
     * "dd/mm/yyyy" → "15 Septembre 2021"
     * "mm/yyyy" → "Septembre 2021"
     */
    public function getFormattedPurchaseDateAttribute(): ?string
    {
        if (empty($this->purchase_date)) {
            return null;
        }

        $dateStr = $this->purchase_date;
        $parts = explode('/', $dateStr);

        $mois_fr = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        // Format: dd/mm/yyyy
        if (count($parts) === 3) {
            $day = (int) $parts[0];
            $month = (int) $parts[1];
            $year = (int) $parts[2];
            return "$day " . $mois_fr[$month] . " $year";
        }
        
        // Format: mm/yyyy
        elseif (count($parts) === 2) {
            $month = (int) $parts[0];
            $year = (int) $parts[1];
            return $mois_fr[$month] . " $year";
        }

        return $dateStr; // Fallback
    }

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
     * Icônes Lucide pour la fréquence d'arrosage.
     */
    public static array $wateringIcons = [
        1 => 'droplet',      // Très rare
        2 => 'droplet',      // Rare
        3 => 'droplets',     // Moyen
        4 => 'droplets',     // Fréquent
        5 => 'waves',        // Quotidien
    ];

    /**
     * Couleurs pour la fréquence d'arrosage.
     */
    public static array $wateringColors = [
        1 => 'gray-400',
        2 => 'blue-400',
        3 => 'blue-500',
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
     * Icônes Lucide pour les besoins en lumière.
     */
    public static array $lightIcons = [
        1 => 'moon',         // Faible lumière
        2 => 'cloud',        // Lumière modérée
        3 => 'sun',          // Lumière moyenne
        4 => 'sun',          // Bonne lumière
        5 => 'sun',          // Soleil direct
    ];

    /**
     * Couleurs pour les besoins en lumière.
     */
    public static array $lightColors = [
        1 => 'gray-600',
        2 => 'yellow-400',
        3 => 'yellow-500',
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

    /**
     * Historique d'arrosage
     */
    public function wateringHistories()
    {
        return $this->hasMany(WateringHistory::class);
    }

    /**
     * Historique de fertilisation
     */
    public function fertilizingHistories()
    {
        return $this->hasMany(FertilizingHistory::class);
    }

    /**
     * Historique de rempotage
     */
    public function repottingHistories()
    {
        return $this->hasMany(ReppotingHistory::class);
    }
}
