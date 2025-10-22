<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;

class GeneratePlantImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plants = Plant::all();
        $created = 0;

        foreach ($plants as $plant) {
            try {
                $imagePath = $this->createPlaceholderImage($plant);
                if ($imagePath) {
                    $plant->update(['main_photo' => $imagePath]);
                    $created++;
                    $this->command->info("✅ Image créée pour: {$plant->name}");
                }
            } catch (\Exception $e) {
                $this->command->warn("⚠️  Erreur pour {$plant->name}: " . $e->getMessage());
            }
        }

        $this->command->info("\n✅ {$created} images créées au total!");
    }

    /**
     * Générer une image placeholder en utilisant GD
     */
    private function createPlaceholderImage(Plant $plant): ?string
    {
        $familyColors = [
            'Orchidaceae' => [139, 71, 137],
            'Araceae' => [45, 80, 22],
            'Asparagaceae' => [85, 107, 47],
            'Lamiaceae' => [139, 115, 85],
            'Rosaceae' => [228, 0, 124],
            'Cactaceae' => [144, 238, 144],
            'Asphodelaceae' => [154, 205, 50],
            'Amaryllidaceae' => [255, 182, 193],
            'Droseraceae' => [139, 0, 0],
            'Nepenthaceae' => [34, 139, 34],
            'Apocynaceae' => [255, 215, 0],
            'Bromeliaceae' => [255, 99, 71],
            'Begoniaceae' => [255, 105, 180],
            'Moraceae' => [143, 188, 143],
            'Marantaceae' => [205, 133, 63],
        ];

        $rgb = $familyColors[$plant->family] ?? [128, 128, 128];

        // Créer une image en utilisant GD
        if (!extension_loaded('gd')) {
            return null;
        }

        $width = 400;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);

        // Remplir avec la couleur
        $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefill($image, 0, 0, $bgColor);

        // Ajouter du texte blanc
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Texte principal
        $text = substr($plant->name, 0, 30);
        $textX = max(10, ($width - strlen($text) * 8) / 2);
        $textY = $height / 2 - 20;
        imagestring($image, 3, $textX, $textY, $text, $textColor);

        // Ajouter la famille en petit au-dessus
        $family = $plant->family ?? 'Plante';
        imagestring($image, 2, 15, 15, $family, $textColor);

        // Sauvegarder l'image
        $filename = 'plants/' . md5($plant->id . $plant->name) . '.jpg';
        $path = storage_path('app/public') . '/' . $filename;

        // Créer le dossier s'il n'existe pas
        @mkdir(dirname($path), 0755, true);

        imagejpeg($image, $path, 85);
        imagedestroy($image);

        return $filename;
    }
}
