<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use App\Models\Photo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class PlantTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des catégories
        $categories = [
            Category::create(['name' => 'Succulentes', 'description' => 'Plantes grasses résistantes']),
            Category::create(['name' => 'Feuillage', 'description' => 'Plantes à feuillage décoratif']),
            Category::create(['name' => 'Fleuries', 'description' => 'Plantes à fleurs colorées']),
            Category::create(['name' => 'Tropicales', 'description' => 'Plantes exotiques']),
            Category::create(['name' => 'Plantes grimpantes', 'description' => 'Plantes à développement vertical']),
        ];

        $plants = [
            [
                'name' => 'Aloe Vera',
                'scientific_name' => 'Aloe barbadensis',
                'category_id' => $categories[0]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 5, // Soleil direct
                'description' => 'Plante succulente très facile d\'entretien avec propriétés apaisantes.',
                'temperature_min' => 13,
                'temperature_max' => 27,
                'humidity_level' => 30,
                'color' => '#90EE90',
            ],
            [
                'name' => 'Monstera Deliciosa',
                'scientific_name' => 'Monstera deliciosa',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante tropicale avec grandes feuilles découpées, très populaire.',
                'temperature_min' => 15,
                'temperature_max' => 26,
                'humidity_level' => 60,
                'color' => '#228B22',
            ],
            [
                'name' => 'Pothos',
                'scientific_name' => 'Epipremnum aureum',
                'category_id' => $categories[4]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 1, // Faible lumière
                'description' => 'Plante grimpante très tolérante, parfaite pour les débutants.',
                'temperature_min' => 12,
                'temperature_max' => 28,
                'humidity_level' => 50,
                'color' => '#32CD32',
            ],
            [
                'name' => 'Sansevieria',
                'scientific_name' => 'Sansevieria trifasciata',
                'category_id' => $categories[0]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 5, // Soleil direct
                'description' => 'Plante très résistante, presque indestructible.',
                'temperature_min' => 13,
                'temperature_max' => 27,
                'humidity_level' => 40,
                'color' => '#2F4F4F',
            ],
            [
                'name' => 'Calathea',
                'scientific_name' => 'Calathea makoyana',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 5, // Quotidien
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante avec motifs spectaculaires sur les feuilles.',
                'temperature_min' => 18,
                'temperature_max' => 27,
                'humidity_level' => 80,
                'color' => '#3CB371',
            ],
            [
                'name' => 'Orchidée',
                'scientific_name' => 'Phalaenopsis amabilis',
                'category_id' => $categories[2]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Fleur élégante et durable, idéale pour la décoration.',
                'temperature_min' => 15,
                'temperature_max' => 25,
                'humidity_level' => 70,
                'color' => '#FF69B4',
            ],
            [
                'name' => 'Fougère de Boston',
                'scientific_name' => 'Nephrolepis exaltata',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 5, // Quotidien
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Fougère élégante aux frondes délicates.',
                'temperature_min' => 15,
                'temperature_max' => 24,
                'humidity_level' => 75,
                'color' => '#6B8E23',
            ],
            [
                'name' => 'Echeveria',
                'scientific_name' => 'Echeveria elegans',
                'category_id' => $categories[0]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 5, // Soleil direct
                'description' => 'Petite succulente en forme de rose, très décorative.',
                'temperature_min' => 10,
                'temperature_max' => 28,
                'humidity_level' => 25,
                'color' => '#FFB6C1',
            ],
            [
                'name' => 'Spathiphyllum',
                'scientific_name' => 'Spathiphyllum wallisii',
                'category_id' => $categories[2]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 1, // Faible lumière
                'description' => 'Plante avec fleurs blanches, purifie l\'air.',
                'temperature_min' => 15,
                'temperature_max' => 29,
                'humidity_level' => 65,
                'color' => '#F0F8FF',
            ],
            [
                'name' => 'Philodendron',
                'scientific_name' => 'Philodendron hederaceum',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante grimpante facile et polyvalente.',
                'temperature_min' => 15,
                'temperature_max' => 27,
                'humidity_level' => 55,
                'color' => '#2E8B57',
            ],
            [
                'name' => 'Anthurium',
                'scientific_name' => 'Anthurium andreanum',
                'category_id' => $categories[2]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Fleur rouge brillante, très ornementale.',
                'temperature_min' => 16,
                'temperature_max' => 28,
                'humidity_level' => 70,
                'color' => '#DC143C',
            ],
            [
                'name' => 'Jade',
                'scientific_name' => 'Crassula ovata',
                'category_id' => $categories[0]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 5, // Soleil direct
                'description' => 'Arbre de jade, succulente ligneuse très élégante.',
                'temperature_min' => 10,
                'temperature_max' => 29,
                'humidity_level' => 30,
                'color' => '#556B2F',
            ],
            [
                'name' => 'Alocasia',
                'scientific_name' => 'Alocasia amazonica',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 5, // Soleil direct
                'description' => 'Plante spectaculaire aux feuilles nervurées.',
                'temperature_min' => 15,
                'temperature_max' => 27,
                'humidity_level' => 65,
                'color' => '#1C1C1C',
            ],
            [
                'name' => 'Scindapsus',
                'scientific_name' => 'Scindapsus pictus',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante grimpante avec feuillage panaché.',
                'temperature_min' => 15,
                'temperature_max' => 26,
                'humidity_level' => 60,
                'color' => '#808000',
            ],
            [
                'name' => 'Begonia',
                'scientific_name' => 'Begonia rex',
                'category_id' => $categories[2]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Begonia royale aux feuilles richement colorées.',
                'temperature_min' => 15,
                'temperature_max' => 25,
                'humidity_level' => 70,
                'color' => '#8B4513',
            ],
            [
                'name' => 'Lierre Anglais',
                'scientific_name' => 'Hedera helix',
                'category_id' => $categories[4]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante grimpante classique et rustique.',
                'temperature_min' => 10,
                'temperature_max' => 22,
                'humidity_level' => 55,
                'color' => '#355E3B',
            ],
            [
                'name' => 'Zamioculcas',
                'scientific_name' => 'Zamioculcas zamiifolia',
                'category_id' => $categories[0]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante ultra-résistante, tolère tous les oublis.',
                'temperature_min' => 15,
                'temperature_max' => 29,
                'humidity_level' => 40,
                'color' => '#4D4D4D',
            ],
            [
                'name' => 'Hibiscus',
                'scientific_name' => 'Hibiscus rosa-sinensis',
                'category_id' => $categories[2]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 5, // Soleil direct
                'description' => 'Fleurs tropicales colorées et spectaculaires.',
                'temperature_min' => 15,
                'temperature_max' => 28,
                'humidity_level' => 60,
                'color' => '#FF4500',
            ],
            [
                'name' => 'Dracaena',
                'scientific_name' => 'Dracaena fragrans',
                'category_id' => $categories[1]->id,
                'watering_frequency' => 3, // Moyen
                'light_requirement' => 3, // Lumière moyenne
                'description' => 'Plante tropicale élancée, excellente pour grands espaces.',
                'temperature_min' => 18,
                'temperature_max' => 27,
                'humidity_level' => 50,
                'color' => '#654321',
            ],
            [
                'name' => 'Tillandsia',
                'scientific_name' => 'Tillandsia cyanea',
                'category_id' => $categories[3]->id,
                'watering_frequency' => 1, // Très rare
                'light_requirement' => 5, // Soleil direct
                'description' => 'Plante aérienne exotique sans terre.',
                'temperature_min' => 15,
                'temperature_max' => 28,
                'humidity_level' => 60,
                'color' => '#FF1493',
            ],
        ];

        // Créer le dossier de stockage s'il n'existe pas
        Storage::makeDirectory('public/plants', 0755, true);

        foreach ($plants as $plantData) {
            $color = $plantData['color'];
            unset($plantData['color']);
            
            $plant = Plant::create($plantData);

            // Créer une image principale
            $mainPhotoPath = $this->generatePlantImage($color, $plant->name, true);
            $plant->main_photo = $mainPhotoPath;
            $plant->save();

            // Créer 2-3 photos supplémentaires
                        // Créer 2-3 photos supplémentaires
            $photoCount = rand(2, 3);
            for ($i = 0; $i < $photoCount; $i++) {
                $photoPath = $this->generatePlantImage($color, $plant->name, false);
                Photo::create([
                    'plant_id' => $plant->id,
                    'filename' => $photoPath,
                    'description' => "Photo " . ($i + 1) . " de " . $plant->name,
                ]);
            }

            $this->command->info("Plante '{$plant->name}' créée avec photos.");
        }

        $this->command->info('20 plantes de test avec photos ont été importées avec succès !');
    }

    private function generatePlantImage($color, $plantName, $isMainPhoto = false)
    {
        $width = $isMainPhoto ? 600 : 300;
        $height = $isMainPhoto ? 600 : 300;
        
        // Créer une image avec GD (built-in en PHP)
        $image = imagecreatetruecolor($width, $height);
        
        // Convertir le code couleur hex en RGB
        $rgb = $this->hexToRgb($color);
        $bgColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        imagefill($image, 0, 0, $bgColor);
        
        // Ajouter du texte blanc
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = $isMainPhoto ? 5 : 4;
        $textX = ($width - (strlen($plantName) * imagefontwidth($fontSize))) / 2;
        $textY = ($height - imagefontheight($fontSize)) / 2;
        imagestring($image, $fontSize, $textX, $textY, $plantName, $textColor);
        
        // Générer un nom de fichier unique
        $uniqueName = \Str::slug($plantName) . '-' . \Str::random(8) . '.jpg';
        $filename = 'plants/' . $uniqueName;
        
        // Chemin complet pour sauvegarder l'image
        $fullPath = storage_path('app/public/' . $filename);
        
        // Créer le dossier s'il n'existe pas
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Sauvegarder l'image
        imagejpeg($image, $fullPath, 80);
        imagedestroy($image);
        
        // Retourner le chemin relatif pour la base de données
        return $filename;
    }
    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        $length = strlen($hex);
        
        if ($length == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
