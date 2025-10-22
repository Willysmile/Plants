<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LightRequirement;

class LightRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            ['number' => 1, 'label' => 'Faible lumière'],
            ['number' => 2, 'label' => 'Lumière modérée'],
            ['number' => 3, 'label' => 'Lumière moyenne'],
            ['number' => 4, 'label' => 'Bonne lumière'],
            ['number' => 5, 'label' => 'Soleil direct'],
        ];

        foreach ($requirements as $requirement) {
            LightRequirement::create($requirement);
        }
    }
}
