<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // 1. Origine climatique
            ['name' => 'Tropicale', 'category' => 'Origine climatique'],
            ['name' => 'Subtropicale', 'category' => 'Origine climatique'],
            ['name' => 'Méditerranéenne', 'category' => 'Origine climatique'],
            ['name' => 'Désertique', 'category' => 'Origine climatique'],
            ['name' => 'Tempérée', 'category' => 'Origine climatique'],
            ['name' => 'Alpine', 'category' => 'Origine climatique'],
            ['name' => 'Équatoriale', 'category' => 'Origine climatique'],

            // 2. Type de feuillage
            ['name' => 'Feuillage persistant', 'category' => 'Type de feuillage'],
            ['name' => 'Feuillage caduc', 'category' => 'Type de feuillage'],
            ['name' => 'Feuillage panaché', 'category' => 'Type de feuillage'],
            ['name' => 'Feuillage décoratif', 'category' => 'Type de feuillage'],
            ['name' => 'Feuillage argenté', 'category' => 'Type de feuillage'],
            ['name' => 'Feuillage pourpre/rouge', 'category' => 'Type de feuillage'],

            // 3. Type de plante
            ['name' => 'Succulente', 'category' => 'Type de plante'],
            ['name' => 'Épiphyte', 'category' => 'Type de plante'],
            ['name' => 'Aquatique', 'category' => 'Type de plante'],
            ['name' => 'Carnivore', 'category' => 'Type de plante'],
            ['name' => 'Aromatique', 'category' => 'Type de plante'],
            ['name' => 'Bulbeuse', 'category' => 'Type de plante'],
            ['name' => 'Rhizomateuse', 'category' => 'Type de plante'],

            // 4. Port de la plante
            ['name' => 'Grimpante', 'category' => 'Port de la plante'],
            ['name' => 'Rampante', 'category' => 'Port de la plante'],
            ['name' => 'Retombante', 'category' => 'Port de la plante'],
            ['name' => 'Arbustive', 'category' => 'Port de la plante'],
            ['name' => 'Arborescente', 'category' => 'Port de la plante'],
            ['name' => 'En rosette', 'category' => 'Port de la plante'],
            ['name' => 'Tapissante', 'category' => 'Port de la plante'],
            ['name' => 'Érigée', 'category' => 'Port de la plante'],

            // 5. Floraison
            ['name' => 'Floraison décorative', 'category' => 'Floraison'],
            ['name' => 'Floraison parfumée', 'category' => 'Floraison'],
            ['name' => 'Floraison longue durée', 'category' => 'Floraison'],
            ['name' => 'Floraison printanière', 'category' => 'Floraison'],
            ['name' => 'Floraison estivale', 'category' => 'Floraison'],
            ['name' => 'Floraison automnale', 'category' => 'Floraison'],
            ['name' => 'Floraison hivernale', 'category' => 'Floraison'],
            ['name' => 'Remontante', 'category' => 'Floraison'],

            // 6. Taille de la plante
            ['name' => 'Miniature', 'category' => 'Taille de la plante'],
            ['name' => 'Petite', 'category' => 'Taille de la plante'],
            ['name' => 'Moyenne', 'category' => 'Taille de la plante'],
            ['name' => 'Grande', 'category' => 'Taille de la plante'],
            ['name' => 'Très grande', 'category' => 'Taille de la plante'],

            // 7. Vitesse de croissance
            ['name' => 'Croissance rapide', 'category' => 'Vitesse de croissance'],
            ['name' => 'Croissance moyenne', 'category' => 'Vitesse de croissance'],
            ['name' => 'Croissance lente', 'category' => 'Vitesse de croissance'],

            // 8. Caractéristiques spéciales
            ['name' => 'Dépolluante', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Mellifère', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Toxique', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Pet-friendly', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Résistante aux maladies', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Résistante au gel', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Résistante à la sécheresse', 'category' => 'Caractéristiques spéciales'],
            ['name' => 'Facile d\'entretien', 'category' => 'Caractéristiques spéciales'],

            // 9. Texture/Aspect
            ['name' => 'Feuillage brillant', 'category' => 'Texture/Aspect'],
            ['name' => 'Feuillage duveteux', 'category' => 'Texture/Aspect'],
            ['name' => 'Feuillage épineux', 'category' => 'Texture/Aspect'],
            ['name' => 'Feuillage charnu', 'category' => 'Texture/Aspect'],
            ['name' => 'Feuillage découpé', 'category' => 'Texture/Aspect'],

            // 10. Système racinaire
            ['name' => 'Racines aériennes', 'category' => 'Système racinaire'],
            ['name' => 'Racines superficielles', 'category' => 'Système racinaire'],
            ['name' => 'Racines profondes', 'category' => 'Système racinaire'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                ['category' => $tag['category']]
            );
        }
    }
}
