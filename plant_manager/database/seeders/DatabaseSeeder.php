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
        // Les utilisateurs de test doivent être créés manuellement via:
        // php artisan tinker
        // User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => Hash::make('password'), 'is_admin' => false])
        // User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => Hash::make('admin_password'), 'is_admin' => true])
        // Ou via la page d'enregistrement (register page)

        $this->call([
            WateringFrequencySeeder::class,
            LightRequirementSeeder::class,
            PurchasePlaceSeeder::class,
            LocationSeeder::class,
            FertilizerTypeSeeder::class,
            TagSeeder::class,
            PlantDataSeeder::class,
        ]);
    }
}
