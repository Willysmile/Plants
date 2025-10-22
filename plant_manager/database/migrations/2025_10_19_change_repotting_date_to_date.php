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
        Schema::table('repotting_history', function (Blueprint $table) {
            // Convertir dateTime en date (garder seulement la date, pas l'heure)
            $table->date('repotting_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repotting_history', function (Blueprint $table) {
            $table->dateTime('repotting_date')->change();
        });
    }
};
