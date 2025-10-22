<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer les catégories de tags
        $categories = [
            'Origine climatique',
            'Type de feuillage',
            'Type de plante',
            'Port de la plante',
            'Floraison',
            'Taille de la plante',
            'Vitesse de croissance',
            'Caractéristiques spéciales',
            'Texture/Aspect',
            'Système racinaire',
        ];

        foreach ($categories as $category) {
            DB::table('tag_categories')->insertOrIgnore([
                'name' => $category,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Note: Les tags ont déjà une colonne 'category' définie lors de leur création par le seeder
        // mais cette colonne n'existe plus dans la table car elle n'est pas définie dans la migration
        // Donc nous ne pouvons pas associer automatiquement les tags aux catégories
        // C'est OK pour le moment - les catégories sont créées et prêtes à être associées manuellement
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Délier les tags des catégories
        DB::table('tags')->update(['tag_category_id' => null]);
        // Supprimer les catégories
        DB::table('tag_categories')->whereIn('name', [
            'Origine climatique',
            'Type de feuillage',
            'Type de plante',
            'Port de la plante',
            'Floraison',
            'Taille de la plante',
            'Vitesse de croissance',
            'Caractéristiques spéciales',
            'Texture/Aspect',
            'Système racinaire',
        ])->delete();
    }
};

