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
        Schema::table('plants', function (Blueprint $table) {
            // Ajouter les colonnes de clés étrangères
            $table->unsignedBigInteger('watering_frequency_id')->nullable()->after('watering_frequency');
            $table->unsignedBigInteger('light_requirement_id')->nullable()->after('light_requirement');
            
            // Ajouter les contraintes de clé étrangère
            $table->foreign('watering_frequency_id')->references('id')->on('watering_frequencies')->onDelete('set null');
            $table->foreign('light_requirement_id')->references('id')->on('light_requirements')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            // Supprimer les clés étrangères
            $table->dropForeign(['watering_frequency_id']);
            $table->dropForeign(['light_requirement_id']);
            
            // Supprimer les colonnes
            $table->dropColumn(['watering_frequency_id', 'light_requirement_id']);
        });
    }
};
