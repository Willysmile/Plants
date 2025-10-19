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
            // Remplacement du scientific_name par une structure botanique détaillée
            $table->string('genus')->nullable()->after('name'); // Genre (ex: Phalaenopsis)
            $table->string('species')->nullable()->after('genus'); // Espèce (ex: amabilis)
            $table->string('subspecies')->nullable()->after('species'); // Sous-espèce
            $table->string('variety')->nullable()->after('subspecies'); // Variété
            $table->string('cultivar')->nullable()->after('variety'); // Cultivar (ex: 'White Dream')
            
            // Le scientific_name devient auto-généré à partir de genus et species
            // On le garde comme champ calculé/stocké pour les requêtes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['genus', 'species', 'subspecies', 'variety', 'cultivar']);
        });
    }
};
