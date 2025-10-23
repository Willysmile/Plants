<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Ajouter les colonnes supplémentaires si elles n'existent pas
            if (!Schema::hasColumn('locations', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('locations', 'room')) {
                $table->string('room')->nullable();
            }
            if (!Schema::hasColumn('locations', 'light_level')) {
                $table->string('light_level')->nullable();
            }
            if (!Schema::hasColumn('locations', 'humidity_level')) {
                $table->integer('humidity_level')->nullable();
            }
            if (!Schema::hasColumn('locations', 'temperature')) {
                $table->decimal('temperature', 5, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Supprimer les colonnes si elles existent
            if (Schema::hasColumn('locations', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('locations', 'room')) {
                $table->dropColumn('room');
            }
            if (Schema::hasColumn('locations', 'light_level')) {
                $table->dropColumn('light_level');
            }
            if (Schema::hasColumn('locations', 'humidity_level')) {
                $table->dropColumn('humidity_level');
            }
            if (Schema::hasColumn('locations', 'temperature')) {
                $table->dropColumn('temperature');
            }
        });
    }
};
