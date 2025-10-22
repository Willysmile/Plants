<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Convert image to WebP format
     *
     * @param string $sourcePath Full path or disk path to source image
     * @param int $quality JPEG/PNG quality (1-100), default 85
     * @return string WebP filename
     */
    public function convertToWebp(string $sourcePath, int $quality = 85): string
    {
        try {
            // Read image from storage
            $image = Image::read($sourcePath);

            // Generate WebP filename
            $filename = pathinfo(basename($sourcePath), PATHINFO_FILENAME) . '.webp';
            $webpPath = 'photos/' . $filename;

            // Encode to WebP and save
            Storage::disk('public')->put(
                $webpPath,
                $image->toWebp($quality)
            );

            return $filename;
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to convert image to WebP: {$e->getMessage()}");
        }
    }

    /**
     * Convert and store uploaded image as WebP
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $quality
     * @return string WebP filename
     */
    public function storeAsWebp($file, int $quality = 85): string
    {
        try {
            // Read uploaded file
            $image = Image::read($file);

            // Generate unique WebP filename
            $filename = Str::random(40) . '.webp';
            $webpPath = 'photos/' . $filename;

            // Encode to WebP and save to public disk
            Storage::disk('public')->put(
                $webpPath,
                $image->toWebp($quality)
            );

            return $filename;
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to store image as WebP: {$e->getMessage()}");
        }
    }

    /**
     * Convert existing image file to WebP (in-place conversion)
     *
     * @param string $filename WebP filename to create
     * @param string $sourcePath Full path to source image
     * @param int $quality
     * @return bool
     */
    public function convertExistingImage(string $sourcePath, int $quality = 85): bool
    {
        try {
            $image = Image::read($sourcePath);
            
            // Replace original file with WebP
            $webpPath = pathinfo($sourcePath, PATHINFO_DIRNAME) . '/' . 
                        pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp';
            
            Storage::disk('public')->put(
                str_replace(Storage::disk('public')->path(''), '', $webpPath),
                $image->toWebp($quality)
            );

            // Delete original file
            @unlink($sourcePath);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get image info (dimensions, size, etc)
     *
     * @param string $path
     * @return array
     */
    public function getImageInfo(string $path): array
    {
        try {
            $image = Image::read($path);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => Storage::disk('public')->size(str_replace(Storage::disk('public')->path(''), '', $path)),
                'format' => pathinfo($path, PATHINFO_EXTENSION),
            ];
        } catch (\Exception $e) {
            return [];
        }
    }
}
