<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the incorrectly typed columns and recreate them with correct types
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_user_id', 'deletion_reason', 'recovery_deadline']);
        });

        Schema::table('plants', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by_user_id')->nullable()->after('deleted_at');
            $table->string('deletion_reason')->nullable()->after('deleted_by_user_id');
            $table->timestamp('recovery_deadline')->nullable()->after('deletion_reason');
        });
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_user_id', 'deletion_reason', 'recovery_deadline']);
        });

        Schema::table('plants', function (Blueprint $table) {
            $table->timestamp('deleted_by_user_id')->nullable()->after('deleted_at');
            $table->string('deletion_reason')->nullable()->after('deleted_by_user_id');
            $table->timestamp('recovery_deadline')->nullable()->after('deletion_reason');
        });
    }
};
