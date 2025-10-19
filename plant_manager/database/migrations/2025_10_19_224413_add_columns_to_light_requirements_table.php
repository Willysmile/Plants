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
        Schema::table('light_requirements', function (Blueprint $table) {
            if (!Schema::hasColumn('light_requirements', 'number')) {
                $table->integer('number')->unique();
            }
            if (!Schema::hasColumn('light_requirements', 'label')) {
                $table->string('label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('light_requirements', function (Blueprint $table) {
            if (Schema::hasColumn('light_requirements', 'number')) {
                $table->dropColumn('number');
            }
            if (Schema::hasColumn('light_requirements', 'label')) {
                $table->dropColumn('label');
            }
        });
    }
};
