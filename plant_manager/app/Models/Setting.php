<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['timezone', 'locale', 'temperature_unit'];

    /**
     * Get the single settings instance
     */
    public static function getInstance()
    {
        return self::firstOrCreate(
            [],
            [
                'timezone' => 'Europe/Paris',
                'locale' => 'fr',
                'temperature_unit' => 'celsius',
            ]
        );
    }
}
