<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Photo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Attache une photo principale à une plante (convertie en WebP).
     */
    public function attachMainPhoto(Plant $plant, UploadedFile $file): void
    {
        // Store original file temporarily
        $tempPath = $file->store("plants/{$plant->id}", 'public');
        $fullPath = Storage::disk('public')->path($tempPath);

        try {
            // Convert to WebP
            $webpFilename = $this->imageService->convertToWebp($fullPath, 85);
            $webpPath = "plants/{$plant->id}/{$webpFilename}";
            
            // Delete original file
            Storage::disk('public')->delete($tempPath);

            // Get WebP file size
            $webpFullPath = Storage::disk('public')->path($webpPath);
            $webpSize = filesize($webpFullPath);

            $plant->update(['main_photo' => $webpPath]);
            
            $plant->photos()->create([
                'filename' => $webpPath,
                'mime_type' => 'image/webp',
                'size' => $webpSize,
                'is_main' => true,
            ]);
        } catch (\Exception $e) {
            // Fallback: keep original file if conversion fails
            Storage::disk('public')->delete($tempPath);
            throw $e;
        }
    }

    /**
     * Attache plusieurs photos à une plante (converties en WebP).
     */
    public function attachPhotos(Plant $plant, array $files): void
    {
        foreach ($files as $file) {
            // Store original file temporarily
            $tempPath = $file->store("plants/{$plant->id}", 'public');
            $fullPath = Storage::disk('public')->path($tempPath);

            try {
                // Convert to WebP
                $webpFilename = $this->imageService->convertToWebp($fullPath, 85);
                $webpPath = "plants/{$plant->id}/{$webpFilename}";
                
                // Delete original file
                Storage::disk('public')->delete($tempPath);

                // Get WebP file size
                $webpFullPath = Storage::disk('public')->path($webpPath);
                $webpSize = filesize($webpFullPath);

                $plant->photos()->create([
                    'filename' => $webpPath,
                    'mime_type' => 'image/webp',
                    'size' => $webpSize,
                    'is_main' => false,
                ]);
            } catch (\Exception $e) {
                // Log error and continue with next file
                Storage::disk('public')->delete($tempPath);
                \Log::error("Failed to convert photo for plant {$plant->id}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Supprime une photo.
     */
    public function deletePhoto(Photo $photo): void
    {
        Storage::disk('public')->delete($photo->filename);
        $photo->delete();
    }

    /**
     * Valide un fichier photo.
     */
    public function validatePhoto(UploadedFile $file): bool
    {
        $maxSize = 1024 * 1024; // 1MB
        $validMimes = ['image/jpeg', 'image/png', 'image/webp'];
        
        return $file->getSize() <= $maxSize &&
               in_array($file->getClientMimeType(), $validMimes);
    }
}
