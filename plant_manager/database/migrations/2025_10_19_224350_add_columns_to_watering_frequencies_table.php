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
        Schema::table('watering_frequencies', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('watering_frequencies', 'number')) {
                $table->integer('number')->unique();
            }
            if (!Schema::hasColumn('watering_frequencies', 'label')) {
                $table->string('label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watering_frequencies', function (Blueprint $table) {
            if (Schema::hasColumn('watering_frequencies', 'number')) {
                $table->dropColumn('number');
            }
            if (Schema::hasColumn('watering_frequencies', 'label')) {
                $table->dropColumn('label');
            }
        });
    }
};
