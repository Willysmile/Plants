<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Compatibilité MySQL et SQLite
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // Vider les tables dans l'ordre inverse des dépendances
        DB::table('plant_tag')->truncate();
        DB::table('photos')->truncate();
        DB::table('watering_history')->truncate();
        DB::table('fertilizing_history')->truncate();
        DB::table('repotting_history')->truncate();
        DB::table('plant_propagations')->truncate();
        DB::table('plants')->truncate();

        // Réactiver les contraintes
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // La migration n'est pas réversible intentionnellement
        // (elle supprime les données)
    }
};
