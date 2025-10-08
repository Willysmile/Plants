<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id(); // id de la photo
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade'); // référence plante
            $table->string('filename'); // nom du fichier / path relatif
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable(); // taille en octets
            $table->string('description')->nullable();
            $table->boolean('is_main')->default(false); // photo principale optionnelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};