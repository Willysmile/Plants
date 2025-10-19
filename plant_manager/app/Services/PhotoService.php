<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Photo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    /**
     * Attache une photo principale à une plante.
     */
    public function attachMainPhoto(Plant $plant, UploadedFile $file): void
    {
        $path = $file->store("plants/{$plant->id}", 'public');
        
        $plant->update(['main_photo' => $path]);
        
        $plant->photos()->create([
            'filename' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'is_main' => true,
        ]);
    }

    /**
     * Attache plusieurs photos à une plante.
     */
    public function attachPhotos(Plant $plant, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store("plants/{$plant->id}", 'public');
            
            $plant->photos()->create([
                'filename' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'is_main' => false,
            ]);
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
