<?php

namespace App\Console\Commands;

use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertPhotosToWebp extends Command
{
    protected $signature = 'photos:convert-to-webp 
                          {--quality=85 : WebP quality (1-100)}
                          {--dry-run : Show what would be converted without actual conversion}';

    protected $description = 'Convert all existing photos to WebP format';

    public function handle()
    {
        $imageService = app(ImageService::class);
        $dryRun = $this->option('dry-run');
        $quality = $this->option('quality');

        $this->info('Starting photo conversion to WebP...');
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $photos = Photo::all();
        $total = $photos->count();
        $converted = 0;
        $failed = 0;
        $skipped = 0;
        $totalSaved = 0;

        $this->withProgressBar($photos, function ($photo) use (
            $imageService,
            $dryRun,
            $quality,
            &$converted,
            &$failed,
            &$skipped,
            &$totalSaved
        ) {
            // Skip if already WebP
            if ($photo->mime_type === 'image/webp') {
                $skipped++;
                return;
            }

            try {
                $sourcePath = Storage::disk('public')->path($photo->filename);

                if (!file_exists($sourcePath)) {
                    $this->line("\n⚠️  File not found: {$photo->filename}");
                    $failed++;
                    return;
                }

                $originalSize = filesize($sourcePath);

                if (!$dryRun) {
                    // Read and convert image
                    $image = \Intervention\Image\ImageManager::gd()->read($sourcePath);
                    
                    // Generate WebP filename with same directory
                    $dirname = dirname($sourcePath);
                    $basename = pathinfo($sourcePath, PATHINFO_FILENAME);
                    $webpFullPath = $dirname . '/' . $basename . '.webp';
                    
                    // Save as WebP with quality parameter
                    $image->toWebp(quality: (int)$quality)->save($webpFullPath);
                    
                    if (file_exists($webpFullPath)) {
                        $webpSize = filesize($webpFullPath);
                        $saved = $originalSize - $webpSize;
                        $totalSaved += $saved;
                        
                        // Update to relative path for storage
                        $webpRelativePath = str_replace(Storage::disk('public')->path(''), '', $webpFullPath);

                        // Update photo record
                        $photo->update([
                            'filename' => $webpRelativePath,
                            'mime_type' => 'image/webp',
                            'size' => $webpSize,
                        ]);

                        // Delete original
                        @unlink($sourcePath);

                        $converted++;
                    } else {
                        $failed++;
                    }
                } else {
                    // Dry run: just calculate savings
                    $estimated = round($originalSize * 0.4); // ~60% reduction expected
                    $totalSaved += ($originalSize - $estimated);
                    $converted++;
                }
            } catch (\Exception $e) {
                $this->line("\n❌ Error converting {$photo->filename}: {$e->getMessage()}");
                $failed++;
            }
        });

        $this->newLine(2);

        // Summary
        $this->info('✅ Conversion Summary:');
        $this->line("Total photos: {$total}");
        $this->line("Converted: {$converted}");
        $this->line("Skipped (already WebP): {$skipped}");
        $this->line("Failed: {$failed}");
        
        if ($totalSaved > 0) {
            $savedMB = round($totalSaved / 1024 / 1024, 2);
            $this->line("Space saved: {$savedMB} MB");
        }

        if ($dryRun) {
            $this->warn("\nThis was a dry run. Run without --dry-run to actually convert.");
        }
    }
}
