<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerateReferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Générer les références pour toutes les plantes
        $plants = Plant::whereNull('reference')->get();
        
        foreach ($plants as $plant) {
            $plant->reference = $plant->generateReference();
            $plant->save();
            echo "✓ {$plant->name}: {$plant->reference}\n";
        }
        
        echo "\n✅ {$plants->count()} références générées !\n";
    }
}
