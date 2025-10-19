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
        Schema::table('fertilizing_history', function (Blueprint $table) {
            // Convertir dateTime en date (garder seulement la date, pas l'heure)
            $table->date('fertilizing_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizing_history', function (Blueprint $table) {
            $table->dateTime('fertilizing_date')->change();
        });
    }
};
