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
            // Changer la colonne purchase_date de date à string pour accepter les formats partiels
            // Format: "dd/mm/yyyy" ou "mm/yyyy"
            $table->string('purchase_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            // Revenir à date si on rollback
            $table->date('purchase_date')->nullable()->change();
        });
    }
};
