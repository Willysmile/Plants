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
        Schema::create('disease_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->onDelete('cascade');
            $table->string('disease_name'); // Nom de la maladie (Ex: Oïdium, Cochenilles, etc.)
            $table->text('description')->nullable(); // Description symptômes
            $table->string('treatment')->nullable(); // Traitement appliqué
            $table->dateTime('detected_at'); // Date de détection
            $table->dateTime('treated_at')->nullable(); // Date du traitement
            $table->enum('status', ['detected', 'treated', 'cured', 'recurring'])->default('detected');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_histories');
    }
};
