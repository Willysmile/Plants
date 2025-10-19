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
        // Supprimer la clé étrangère d'abord
        Schema::table('plants', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Supprimer la table categories
        Schema::dropIfExists('categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Recréer la colonne et la clé étrangère dans plants
        Schema::table('plants', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('description');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }
};
