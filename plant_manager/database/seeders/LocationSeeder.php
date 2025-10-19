<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'Windowsill salon',
            'Coin salon',
            'Bureau',
            'Étagère cuisine',
            'Étagère murale',
            'Salle de bain',
            'Salon',
            'Coin séjour',
            'Entrée',
            'Étagère salon',
            'Balcon',
            'Balcon cuisine',
            'Jardin',
            'Fenêtre sud',
            'Salon ombre',
            'Bureau ombre',
            'Salon lumineux',
            'Fenêtre lumineuse',
            'Serre humide',
            'Rebord fenêtre',
            'Treillage salon',
            'Table basse',
            'Suspendu fenêtre',
            'Serre orchidée',
            'Collection spéciale',
            'Collection premium',
            'Terrarium',
            'Serre froide',
        ];

        foreach ($locations as $location) {
            if (!empty($location)) {
                Location::firstOrCreate(['name' => $location]);
            }
        }
    }
}
