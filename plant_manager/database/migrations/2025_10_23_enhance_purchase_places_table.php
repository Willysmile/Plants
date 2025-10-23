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
        Schema::table('purchase_places', function (Blueprint $table) {
            // Ajouter les colonnes supplémentaires si elles n'existent pas
            if (!Schema::hasColumn('purchase_places', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('purchase_places', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('purchase_places', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('purchase_places', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('purchase_places', 'type')) {
                $table->string('type')->nullable()->comment('pépinière, jardinerie, marché, etc.');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_places', function (Blueprint $table) {
            // Supprimer les colonnes si elles existent
            if (Schema::hasColumn('purchase_places', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('purchase_places', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('purchase_places', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('purchase_places', 'website')) {
                $table->dropColumn('website');
            }
            if (Schema::hasColumn('purchase_places', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
