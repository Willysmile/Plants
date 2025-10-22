<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    // Champs autorisés à l'assignation de masse
    protected $fillable = [
        'name',
        'scientific_name',
        'reference',
        'family',
        'subfamily',
        'genus',
        'species',
        'subspecies',
        'variety',
        'cultivar',
        'purchase_date',
        'purchase_place',
        'purchase_price',
        'description',
        'watering_frequency',
        'watering_frequency_id',
        'last_watering_date',
        'light_requirement',
        'light_requirement_id',
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
        'is_archived',
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
     * Accessor: Génère automatiquement le nom scientifique à partir de genus et species
     * Format: "Phalaenopsis amabilis"
     */
    public function getGeneratedScientificNameAttribute(): ?string
    {
        if (empty($this->genus) && empty($this->species)) {
            return $this->scientific_name; // Fallback au nom scientifique stocké
        }

        $parts = [];
        if ($this->genus) $parts[] = $this->genus;
        if ($this->species) $parts[] = $this->species;

        return implode(' ', $parts) ?: null;
    }

    /**
     * Accessor: Génère le nom complet avec cultivar et variété
     * Format: "Phalaenopsis amabilis 'White Dream'"
     * ou "Phalaenopsis amabilis subsp. rosenstromii var. alba 'Pink Dream'"
     */
    public function getFullNameAttribute(): ?string
    {
        $base = $this->generated_scientific_name;
        
        if (!$base) {
            return $this->name; // Fallback au nom commun
        }

        $parts = [$base];

        // Ajouter subspecies si présent
        if ($this->subspecies) {
            $parts[] = "subsp. {$this->subspecies}";
        }

        // Ajouter variety si présent
        if ($this->variety) {
            $parts[] = "var. {$this->variety}";
        }

        // Ajouter cultivar si présent (entre guillemets)
        if ($this->cultivar) {
            $parts[] = "'{$this->cultivar}'";
        }

        return implode(' ', $parts);
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
     * Fréquence d'arrosage (relation vers watering_frequencies)
     */
    public function wateringFrequencyData()
    {
        return $this->belongsTo(WateringFrequency::class, 'watering_frequency_id');
    }

    /**
     * Besoin en lumière (relation vers light_requirements)
     */
    public function lightRequirementData()
    {
        return $this->belongsTo(LightRequirement::class, 'light_requirement_id');
    }

    /**
     * Historiques "Infos Diverses" de la plante
     */
    public function histories()
    {
        return $this->hasMany(PlantHistory::class);
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
        return $this->hasMany(RepottingHistory::class);
    }

    /**
     * 🔧 Boot the model - Générer la référence automatiquement lors de la création
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Si aucune référence n'est fournie, la générer automatiquement
            if (empty($model->reference) && !empty($model->family)) {
                $model->reference = $model->generateReference();
            }
        });
    }

    /**
     * 🔧 FIX: Générer une référence automatique basée sur la famille
     * Format: "ORCHI-001" (5 premières lettres de la famille + numéro séquentiel)
     * Cherche le MAX numéro (incluant les soft-deleted)
     */
    public function generateReference(): string
    {
        // Obtenir les 5 premières lettres de la famille en majuscules
        $familyPrefix = strtoupper(substr($this->family ?? 'UNKWN', 0, 5));
        
        // 🔧 FIX: Chercher le MAX numéro existant (y compris soft-deleted!)
        // Car la contrainte UNIQUE s'applique même aux soft-deleted
        $maxNumber = Plant::withTrashed()
            ->where('reference', 'like', $familyPrefix . '-%')
            ->get()
            ->map(function($plant) {
                // Extraire le numéro de la référence (ex: "BROME-001" → 1)
                return (int) substr($plant->reference, -3);
            })
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;
        
        // Retourner la référence formatée (ex: "ORCHI-001")
        return $familyPrefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}

