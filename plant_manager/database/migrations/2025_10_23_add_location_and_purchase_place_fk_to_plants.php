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
            // Ajouter les foreign keys pour location et purchase_place
            $table->unsignedBigInteger('location_id')->nullable()->after('main_photo');
            $table->unsignedBigInteger('purchase_place_id')->nullable()->after('purchase_price');
            
            // Ajouter les contraintes de clé étrangère
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('purchase_place_id')->references('id')->on('purchase_places')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            // Supprimer les clés étrangères
            $table->dropForeign(['location_id']);
            $table->dropForeign(['purchase_place_id']);
            
            // Supprimer les colonnes
            $table->dropColumn(['location_id', 'purchase_place_id']);
        });
    }
};
