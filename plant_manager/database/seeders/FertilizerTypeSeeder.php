<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FertilizerType;

class FertilizerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'NPK', 'description' => 'Engrais équilibré NPK (Azote, Phosphore, Potassium)'],
            ['name' => 'Organique', 'description' => 'Engrais d\'origine organique (compost, fumier, etc.)'],
            ['name' => 'Minéral', 'description' => 'Engrais minéral synthétique'],
            ['name' => 'Liquide', 'description' => 'Engrais liquide soluble'],
            ['name' => 'Granulé', 'description' => 'Engrais granulé à libération lente'],
            ['name' => 'Azote', 'description' => 'Riche en azote (N) pour la croissance foliaire'],
            ['name' => 'Phosphore', 'description' => 'Riche en phosphore (P) pour les racines et fleurs'],
            ['name' => 'Potassium', 'description' => 'Riche en potassium (K) pour la résistance'],
        ];

        foreach ($types as $type) {
            FertilizerType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
