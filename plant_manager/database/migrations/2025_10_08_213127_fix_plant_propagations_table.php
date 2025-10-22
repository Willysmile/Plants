<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer la table existante si nécessaire
        Schema::dropIfExists('plant_propagations');
        
        // Recréer avec la bonne structure
        Schema::create('plant_propagations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('plants')->onDelete('cascade');
            $table->foreignId('daughter_id')->constrained('plants')->onDelete('cascade');
            $table->string('method')->nullable(); // bouture, division, semis...
            $table->date('propagation_date')->nullable();
            $table->timestamps();

            $table->unique(['parent_id','daughter_id']); // éviter doublons
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_propagations');
    }
};