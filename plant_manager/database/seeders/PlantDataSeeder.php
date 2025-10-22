<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\Photo;
use App\Models\PlantHistory;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlantDataSeeder extends Seeder
{
    /**
     * Plant data for seeding
     */
    private array $plantTemplates = [
        ['name' => 'Monstera Deliciosa', 'scientific_name' => 'Rhaphidophora tetrasperma', 'family' => 'Araceae', 'description' => 'Grande plante d\'intérieur avec feuilles perforées'],
        ['name' => 'Pothos Doré', 'scientific_name' => 'Epipremnum aureum', 'family' => 'Araceae', 'description' => 'Plante grimpante facile d\'entretien'],
        ['name' => 'Ficus Lyrata', 'scientific_name' => 'Ficus lyrata', 'family' => 'Moraceae', 'description' => 'Plante à grandes feuilles en forme de lyre'],
        ['name' => 'Palmier Areca', 'scientific_name' => 'Dypsis lutescens', 'family' => 'Arecaceae', 'description' => 'Palmier tropical élégant'],
        ['name' => 'Calathéa Orbifolia', 'scientific_name' => 'Goeppertia orbifolia', 'family' => 'Marantaceae', 'description' => 'Plante d\'intérieur avec feuilles décoratives'],
        ['name' => 'Sansevieria Trifasciata', 'scientific_name' => 'Sansevieria trifasciata', 'family' => 'Asparagaceae', 'description' => 'Succulente robuste et tolérante'],
        ['name' => 'Philodendron Pink', 'scientific_name' => 'Philodendron hederaceum', 'family' => 'Araceae', 'description' => 'Plante aux feuilles roses'],
        ['name' => 'Zamioculcas Zamiifolia', 'scientific_name' => 'Zamioculcas zamiifolia', 'family' => 'Araceae', 'description' => 'Plante très rustique'],
        ['name' => 'Succulente Echeveria', 'scientific_name' => 'Echeveria pulvinata', 'family' => 'Crassulaceae', 'description' => 'Succulente en rosette'],
        ['name' => 'Orchidée Phalaenopsis', 'scientific_name' => 'Phalaenopsis amabilis', 'family' => 'Orchidaceae', 'description' => 'Orchidée d\'intérieur élégante'],
        ['name' => 'Tillandsia Air Plant', 'scientific_name' => 'Tillandsia cyanea', 'family' => 'Bromeliaceae', 'description' => 'Plante aérienne sans soil'],
        ['name' => 'Raphidophora Tetrasperma', 'scientific_name' => 'Rhaphidophora tetrasperma', 'family' => 'Araceae', 'description' => 'Mini monstera compacte'],
        ['name' => 'Scindapsus Pictus', 'scientific_name' => 'Scindapsus pictus', 'family' => 'Araceae', 'description' => 'Plante aux feuilles panachées'],
        ['name' => 'Anthurium Clarinervium', 'scientific_name' => 'Anthurium clarinervium', 'family' => 'Araceae', 'description' => 'Anthurium aux nervures blanches'],
        ['name' => 'Alocasia Polly', 'scientific_name' => 'Alocasia amazonica', 'family' => 'Araceae', 'description' => 'Plante aux feuilles imposantes'],
        ['name' => 'Ceropegia Woodii', 'scientific_name' => 'Ceropegia linearis subsp. woodii', 'family' => 'Apocynaceae', 'description' => 'Plante succulente retombante'],
        ['name' => 'Peperomia Obtusifolia', 'scientific_name' => 'Peperomia obtusifolia', 'family' => 'Piperaceae', 'description' => 'Petite plante charnue'],
        ['name' => 'Hoya Carnosa', 'scientific_name' => 'Hoya carnosa', 'family' => 'Apocynaceae', 'description' => 'Plante grimpante à fleurs étoilées'],
        ['name' => 'Dracaena Marginata', 'scientific_name' => 'Dracaena marginata', 'family' => 'Asparagaceae', 'description' => 'Plante aux feuilles étroites et rouges'],
        ['name' => 'Beaucarnea Recurvata', 'scientific_name' => 'Beaucarnea recurvata', 'family' => 'Asparagaceae', 'description' => 'Plante succulente avec tronc gonflé'],
        ['name' => 'Fittonia Albivenis', 'scientific_name' => 'Fittonia albivenis', 'family' => 'Acanthaceae', 'description' => 'Petite plante aux nervures blanches'],
        ['name' => 'Maranta Leuconeura', 'scientific_name' => 'Maranta leuconeura', 'family' => 'Marantaceae', 'description' => 'Plante aux feuilles mouvantes'],
        ['name' => 'Epipremnum Pinnatum', 'scientific_name' => 'Epipremnum pinnatum', 'family' => 'Araceae', 'description' => 'Plante grimpante aux grandes feuilles'],
        ['name' => 'Monstera Adansonii', 'scientific_name' => 'Rhaphidophora adansonii', 'family' => 'Araceae', 'description' => 'Mini monstera retombante'],
        ['name' => 'Syngonium Podophyllum', 'scientific_name' => 'Syngonium podophyllum', 'family' => 'Araceae', 'description' => 'Plante semi-grimpante compacte'],
        ['name' => 'Aglaonema Commutatum', 'scientific_name' => 'Aglaonema commutatum', 'family' => 'Araceae', 'description' => 'Plante aux feuilles panachées d\'argent'],
        ['name' => 'Asplenium Nidus', 'scientific_name' => 'Asplenium nidus', 'family' => 'Aspleniaceae', 'description' => 'Fougère en nid d\'oiseau'],
        ['name' => 'Chlorophytum Comosum', 'scientific_name' => 'Chlorophytum comosum', 'family' => 'Asparagaceae', 'description' => 'Plante araignée très rustique'],
        ['name' => 'Platycerium Bifurcatum', 'scientific_name' => 'Platycerium bifurcatum', 'family' => 'Polypodiaceae', 'description' => 'Fougère corne de cerf'],
        ['name' => 'Rhaphidophora Halucinans', 'scientific_name' => 'Rhaphidophora decursiva', 'family' => 'Araceae', 'description' => 'Plante aux pétioles bleu-noir'],
        ['name' => 'Microcitrus Australasica', 'scientific_name' => 'Microcitrus australasica', 'family' => 'Rutaceae', 'description' => 'Petit agrume ornemental'],
        ['name' => 'Lithops Pseudotruncatella', 'scientific_name' => 'Lithops pseudotruncatella', 'family' => 'Aizoaceae', 'description' => 'Succulente "pierre vivante"'],
        ['name' => 'Gasteria Verrucosa', 'scientific_name' => 'Gasteria verrucosa', 'family' => 'Asphodelaceae', 'description' => 'Succulente en forme de langue'],
        ['name' => 'Haworthia Fasciata', 'scientific_name' => 'Haworthia fasciata', 'family' => 'Asphodelaceae', 'description' => 'Petite succulente en rosette'],
        ['name' => 'Kalanchoe Thyrsiflora', 'scientific_name' => 'Kalanchoe thyrsiflora', 'family' => 'Crassulaceae', 'description' => 'Succulente aux feuilles jaune-rouge'],
        ['name' => 'Aeonium Arboreum', 'scientific_name' => 'Aeonium arboreum', 'family' => 'Crassulaceae', 'description' => 'Succulente en rosette arborescente'],
        ['name' => 'Sedum Morganianum', 'scientific_name' => 'Sedum morganianum', 'family' => 'Crassulaceae', 'description' => 'Succulente retombante "queue de burro"'],
        ['name' => 'Sempervivum Tectorum', 'scientific_name' => 'Sempervivum tectorum', 'family' => 'Crassulaceae', 'description' => 'Succulente très rustique'],
        ['name' => 'Opuntia Ficus-Indica', 'scientific_name' => 'Opuntia ficus-indica', 'family' => 'Cactaceae', 'description' => 'Cactus figuier de Barbarie'],
        ['name' => 'Cereus Peruvianus', 'scientific_name' => 'Cereus azureus', 'family' => 'Cactaceae', 'description' => 'Cactus columaire bleu'],
    ];

    private array $historiques = [
        'Plante achetée au marché local',
        'Repotée dans un pot plus grand',
        'Première floraison observée',
        'Traitement contre les parasites',
        'Rempotage avec nouveau terreau',
        'Nouveau feuillage apparu',
        'Plante très belle cette semaine',
        'Arrosage réduit pour l\'hiver',
        'Boutures prélevées avec succès',
        'Plante vigoureuse et saine',
        'Nouvelle pousse au sommet',
        'Feuilles jaunes observées, enquête en cours',
    ];

    private array $locations = ['Bureau', 'Salon', 'Chambre', 'Cuisine', 'Terrasse', 'Fenêtre', 'Coin ombragé', 'Rebord de fenêtre'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();
        $locations = ['Bureau', 'Salon', 'Chambre', 'Cuisine', 'Terrasse', 'Fenêtre', 'Coin ombragé', 'Rebord de fenêtre'];

        foreach ($this->plantTemplates as $template) {
            // Créer la plante
            $plant = Plant::create([
                'name' => $template['name'],
                'scientific_name' => $template['scientific_name'],
                'family' => $template['family'],
                'reference' => 'REF-' . Str::random(8),
                'description' => $template['description'],
                'location' => $locations[array_rand($locations)],
                'watering_frequency' => rand(3, 7),
                'watering_frequency_id' => \App\Models\WateringFrequency::inRandomOrder()->first()?->id,
                'light_requirement' => rand(1, 5),
                'light_requirement_id' => \App\Models\LightRequirement::inRandomOrder()->first()?->id,
                'temperature_min' => random_int(10, 18),
                'temperature_max' => random_int(22, 30),
                'humidity_level' => random_int(40, 80),
                'is_archived' => fake()->boolean(10),
            ]);

            // Ajouter des tags aléatoires
            $randomTags = $tags->random(rand(2, 5));
            $plant->tags()->attach($randomTags);

            // Créer des photos (1 à 5)
            $photoCount = rand(1, 5);
            for ($i = 0; $i < $photoCount; $i++) {
                $this->createFakePhoto($plant, $i === 0);
            }

            // Créer un historique aléatoire (0-5 entrées)
            $historyCount = rand(0, 5);
            for ($j = 0; $j < $historyCount; $j++) {
                PlantHistory::create([
                    'plant_id' => $plant->id,
                    'body' => $this->historiques[array_rand($this->historiques)],
                    'created_at' => now()->subDays(rand(0, 90)),
                ]);
            }
        }
    }

    /**
     * Create a fake photo for a plant
     */
    private function createFakePhoto(Plant $plant, bool $isMain = false): void
    {
        // Créer un répertoire pour la plante
        $photoDir = "plants/{$plant->id}";
        if (!Storage::disk('public')->exists($photoDir)) {
            Storage::disk('public')->makeDirectory($photoDir);
        }

        // Générer une image de placeholder avec une couleur aléatoire
        $filename = Str::uuid() . '.jpg';
        $filepath = $photoDir . '/' . $filename;

        // Créer une image simple avec GD
        $img = imagecreatetruecolor(400, 300);
        
        // Couleur aléatoire
        $r = rand(50, 200);
        $g = rand(50, 200);
        $b = rand(50, 200);
        $color = imagecolorallocate($img, $r, $g, $b);

        // Remplir de couleur
        imagefilledrectangle($img, 0, 0, 400, 300, $color);

        // Ajouter du texte
        $textColor = imagecolorallocate($img, 255, 255, 255);
        imagestring($img, 5, 150, 140, $plant->name, $textColor);

        // Sauvegarder temporairement
        $tempPath = storage_path('app/temp_photo.jpg');
        imagejpeg($img, $tempPath, 85);
        imagedestroy($img);

        // Copier vers le storage public
        $content = file_get_contents($tempPath);
        Storage::disk('public')->put($filepath, $content);
        unlink($tempPath);

        // Créer l'enregistrement photo
        Photo::create([
            'plant_id' => $plant->id,
            'filename' => $filepath,
            'description' => fake()->optional()->sentence(),
            'is_main' => $isMain,
        ]);
    }
}

