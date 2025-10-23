<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Créer la table diseases
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Ajouter disease_id à disease_histories
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->foreignId('disease_id')->nullable()->constrained('diseases')->onDelete('cascade')->after('plant_id');
        });

        // 3. Migrer les noms de maladies existantes vers la table diseases
        $diseases = DB::table('disease_histories')
            ->select('disease_name')
            ->whereNotNull('disease_name')
            ->distinct()
            ->get();

        foreach ($diseases as $disease) {
            DB::table('diseases')->updateOrInsert(
                ['name' => $disease->disease_name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 4. Remplir disease_id basé sur disease_name
        DB::statement("
            UPDATE disease_histories dh
            INNER JOIN diseases d ON dh.disease_name = d.name
            SET dh.disease_id = d.id
        ");

        // 5. Supprimer l'ancienne colonne disease_name
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->dropColumn('disease_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer disease_name avant de supprimer la colonne disease_id
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->string('disease_name')->nullable()->after('plant_id');
        });

        // Restaurer les noms
        DB::statement("
            UPDATE disease_histories dh
            INNER JOIN diseases d ON dh.disease_id = d.id
            SET dh.disease_name = d.name
        ");

        // Supprimer la clé étrangère et la colonne
        Schema::table('disease_histories', function (Blueprint $table) {
            $table->dropForeignKeyConstraints();
            $table->dropColumn('disease_id');
        });

        // Supprimer la table diseases
        Schema::dropIfExists('diseases');
    }
};
