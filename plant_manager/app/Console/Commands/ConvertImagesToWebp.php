<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class ConvertImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp {--dry-run : Preview changes without making them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert existing JPG/PNG images to WebP format';

    /**
     * Execute the console command.
     */
    public function handle(ImageService $imageService): int
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        // Find all photos that are not WebP
        $photos = Photo::where('mime_type', '!=', 'image/webp')
            ->orWhere('filename', 'not like', '%.webp')
            ->get();

        if ($photos->isEmpty()) {
            $this->info('✓ Toutes les images sont déjà au format WebP');
            return 0;
        }

        $this->info("Conversion de {$photos->count()} images...");
        $this->newLine();

        $converted = 0;
        $failed = 0;

        foreach ($photos as $photo) {
            $sourcePath = Storage::disk('public')->path($photo->filename);

            // Skip if file doesn't exist
            if (!file_exists($sourcePath)) {
                $this->warn("⚠️  {$photo->filename} - Fichier non trouvé");
                $failed++;
                continue;
            }

            try {
                $this->line("⏳ Conversion: {$photo->filename}");

                // Convert to WebP
                $webpFilename = $imageService->convertToWebp($sourcePath, 85);
                $webpPath = dirname($photo->filename) . '/' . $webpFilename;

                if (!$dryRun) {
                    // Update database
                    $photo->update([
                        'filename' => $webpPath,
                        'mime_type' => 'image/webp',
                    ]);

                    // Delete original file if conversion succeeded
                    if (file_exists($sourcePath)) {
                        @unlink($sourcePath);
                    }

                    // Update main_photo reference if it points to original
                    if ($photo->plant->main_photo === $photo->filename) {
                        $photo->plant->update(['main_photo' => $webpPath]);
                    }
                }

                $this->info("   ✓ Converti en: {$webpPath}");
                $converted++;

            } catch (\Exception $e) {
                $this->error("   ✗ Erreur: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        $this->info("📊 Résumé:");
        $this->info("   ✓ Convertis: {$converted}");
        $this->error("   ✗ Erreurs: {$failed}");

        if ($dryRun) {
            $this->info("\n💡 Exécutez sans --dry-run pour appliquer les changements");
        }

        return 0;
    }
}
