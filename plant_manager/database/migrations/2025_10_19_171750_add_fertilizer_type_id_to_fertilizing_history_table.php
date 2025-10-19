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
            $table->foreignId('fertilizer_type_id')->nullable()->after('plant_id')->constrained('fertilizer_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizing_history', function (Blueprint $table) {
            $table->dropForeignIdFor('FertilizerType');
            $table->dropColumn('fertilizer_type_id');
        });
    }
};
