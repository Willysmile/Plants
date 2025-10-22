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
        // Mapping des tags vers leurs catégories
        $tagCategoryMap = [
            'Origine climatique' => [
                'Tropicale', 'Subtropicale', 'Méditerranéenne', 'Désertique',
                'Tempérée', 'Alpine', 'Équatoriale'
            ],
            'Type de feuillage' => [
                'Feuillage persistant', 'Feuillage caduc', 'Feuillage panaché',
                'Feuillage décoratif', 'Feuillage argenté', 'Feuillage pourpre/rouge'
            ],
            'Type de plante' => [
                'Succulente', 'Épiphyte', 'Aquatique', 'Carnivore',
                'Aromatique', 'Bulbeuse', 'Rhizomateuse'
            ],
            'Port de la plante' => [
                'Grimpante', 'Rampante', 'Retombante', 'Arbustive',
                'Arborescente', 'En rosette', 'Tapissante', 'Érigée'
            ],
            'Floraison' => [
                'Floraison décorative', 'Floraison parfumée', 'Floraison longue durée',
                'Floraison printanière', 'Floraison estivale', 'Floraison automnale',
                'Floraison hivernale', 'Remontante'
            ],
            'Taille de la plante' => [
                'Miniature', 'Petite', 'Moyenne', 'Grande', 'Très grande'
            ],
            'Vitesse de croissance' => [
                'Croissance rapide', 'Croissance moyenne', 'Croissance lente'
            ],
            'Caractéristiques spéciales' => [
                'Dépolluante', 'Mellifère', 'Toxique', 'Pet-friendly',
                'Résistante aux maladies', 'Résistante au gel', 'Résistante à la sécheresse',
                'Facile d\'entretien'
            ],
            'Texture/Aspect' => [
                'Feuillage brillant', 'Feuillage duveteux', 'Feuillage épineux',
                'Feuillage charnu', 'Feuillage découpé'
            ],
            'Système racinaire' => [
                'Racines aériennes', 'Racines superficielles', 'Racines profondes'
            ],
        ];

        // Associer les tags aux catégories
        foreach ($tagCategoryMap as $categoryName => $tagNames) {
            $categoryId = DB::table('tag_categories')
                          ->where('name', $categoryName)
                          ->value('id');
            
            if ($categoryId) {
                DB::table('tags')
                    ->whereIn('name', $tagNames)
                    ->update(['tag_category_id' => $categoryId, 'updated_at' => now()]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tags')->update(['tag_category_id' => null]);
    }
};
