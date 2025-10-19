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
            $table->string('old_pot_unit')->nullable()->after('old_pot_size');
            $table->string('new_pot_unit')->nullable()->after('new_pot_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repotting_history', function (Blueprint $table) {
            $table->dropColumn(['old_pot_unit', 'new_pot_unit']);
        });
    }
};
