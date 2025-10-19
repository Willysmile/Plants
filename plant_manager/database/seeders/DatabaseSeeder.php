<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur de test seulement s'il n'existe pas
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        $this->call([
            WateringFrequencySeeder::class,
            LightRequirementSeeder::class,
            PurchasePlaceSeeder::class,
            LocationSeeder::class,
            FertilizerTypeSeeder::class,
            TagSeeder::class,
        ]);
    }
}
