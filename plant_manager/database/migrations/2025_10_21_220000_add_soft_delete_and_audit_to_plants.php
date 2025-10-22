<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft-delete and audit columns to plants
        Schema::table('plants', function (Blueprint $table) {
            $table->softDeletes()->nullable();
            $table->timestamp('deleted_by_user_id')->nullable()->after('deleted_at');
            $table->string('deletion_reason')->nullable()->after('deleted_by_user_id');
            $table->timestamp('recovery_deadline')->nullable()->after('deletion_reason');
        });

        // Add soft-delete columns to photos
        Schema::table('photos', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });

        // Add soft-delete columns to plant_histories
        Schema::table('plant_histories', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['deleted_by_user_id', 'deletion_reason', 'recovery_deadline']);
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('plant_histories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
