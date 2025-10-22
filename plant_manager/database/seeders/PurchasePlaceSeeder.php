<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchasePlace;

class PurchasePlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $places = [
            'Jardinerie locale',
            'Marché',
            'jardiland',
            'tttt',
        ];

        foreach ($places as $place) {
            if (!empty($place)) {
                PurchasePlace::firstOrCreate(['name' => $place]);
            }
        }
    }
}
