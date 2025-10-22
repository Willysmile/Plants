<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WateringFrequency;

class WateringFrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frequencies = [
            ['number' => 1, 'label' => 'Très rare'],
            ['number' => 2, 'label' => 'Rare'],
            ['number' => 3, 'label' => 'Moyen'],
            ['number' => 4, 'label' => 'Fréquent'],
            ['number' => 5, 'label' => 'Quotidien'],
        ];

        foreach ($frequencies as $frequency) {
            WateringFrequency::create($frequency);
        }
    }
}
