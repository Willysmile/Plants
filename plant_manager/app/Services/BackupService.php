<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\PlantHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class BackupService
{
    const EXPORT_FORMAT_VERSION = 'plants-backup-v1';
    const BACKUP_DIR = 'backups';

    /**
     * Export all plants data to a ZIP archive with JSON + photos
     */
    public function export(array $options = []): string
    {
        $includePhotos = $options['include_photos'] ?? true;
        $compress = $options['compress'] ?? true;

        // Collect data
        $data = $this->collectData();

        // Generate export file
        $exportId = Str::uuid();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "export_{$timestamp}_{$exportId}.zip";
        $filepath = storage_path("app/" . self::BACKUP_DIR . "/{$filename}");

        // Ensure backup dir exists
        $this->ensureBackupDir();

        // Create ZIP
        $zip = new ZipArchive();
        if ($zip->open($filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Failed to create ZIP archive at {$filepath}");
        }

        try {
            // Add JSON data
            $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $checksum = hash('sha256', $jsonContent);

            $zip->addFromString('backup.json', $jsonContent);

            // Add metadata
            $metadata = [
                'export_format' => self::EXPORT_FORMAT_VERSION,
                'app_version' => config('app.version', 'unknown'),
                'exported_at' => now()->toIso8601String(),
                'exporter_id' => auth()->id() ?? null,
                'counts' => [
                    'plants' => count($data['plants']),
                    'tags' => count($data['tags']),
                    'plant_histories' => count($data['plant_histories']),
                    'photos' => 0, // Will update if photos included
                ],
                'integrity' => [
                    'checksum_sha256' => $checksum,
                    'photos_included' => $includePhotos,
                ],
            ];

            // Add photos if requested
            if ($includePhotos) {
                $photoCount = $this->addPhotosToZip($zip, $data['photos']);
                $metadata['counts']['photos'] = $photoCount;
            }

            $metadataJson = json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $zip->addFromString('metadata.json', $metadataJson);

            $zip->close();

            // Log export
            $this->logBackup('export', [
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => filesize($filepath),
                'checksum' => $checksum,
                'metadata' => $metadata,
            ]);

            return $filename;
        } catch (\Exception $e) {
            $zip->close();
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            throw $e;
        }
    }

    /**
     * Collect all data for export
     */
    private function collectData(): array
    {
        $plants = Plant::with('tags', 'histories')
            ->get()
            ->map(fn($plant) => [
                'id' => $plant->id,
                'name' => $plant->name,
                'scientific_name' => $plant->scientific_name,
                'family' => $plant->family,
                'subfamily' => $plant->subfamily,
                'genus' => $plant->genus,
                'species' => $plant->species,
                'subspecies' => $plant->subspecies,
                'variety' => $plant->variety,
                'cultivar' => $plant->cultivar,
                'reference' => $plant->reference,
                'description' => $plant->description,
                'location' => $plant->location,
                'notes' => $plant->notes,
                'watering_frequency' => $plant->watering_frequency,
                'light_requirement' => $plant->light_requirement,
                'temperature_min' => $plant->temperature_min,
                'temperature_max' => $plant->temperature_max,
                'humidity_level' => $plant->humidity_level,
                'main_photo' => $plant->main_photo,
                'created_at' => $plant->created_at->toIso8601String(),
                'updated_at' => $plant->updated_at->toIso8601String(),
                'is_archived' => $plant->is_archived,
                'tags' => $plant->tags->map(fn($tag) => $tag->name)->toArray(),
                'histories' => $plant->histories->map(fn($h) => [
                    'body' => $h->body,
                    'created_at' => $h->created_at->toIso8601String(),
                ])->toArray(),
            ])
            ->toArray();

        $tags = \App\Models\Tag::select('id', 'name', 'category')->get()->toArray();

        $histories = PlantHistory::select('id', 'plant_id', 'body', 'created_at')
            ->get()
            ->map(fn($h) => $h->toArray())
            ->toArray();

        $photos = \App\Models\Photo::select('id', 'plant_id', 'filename', 'description', 'is_main', 'created_at')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'plant_id' => $p->plant_id,
                'filename' => $p->filename,
                'description' => $p->description,
                'is_main' => $p->is_main,
                'created_at' => $p->created_at->toIso8601String(),
            ])
            ->toArray();

        return [
            'plants' => $plants,
            'tags' => $tags,
            'plant_histories' => $histories,
            'photos' => $photos,
        ];
    }

    /**
     * Add photos to ZIP archive
     */
    private function addPhotosToZip(ZipArchive $zip, array $photos): int
    {
        $count = 0;
        $disk = Storage::disk('public');

        foreach ($photos as $photo) {
            $filename = $photo['filename'];
            if ($disk->exists($filename)) {
                $content = $disk->get($filename);
                $zipPath = 'photos/' . basename($filename);
                $zip->addFromString($zipPath, $content);
                $count++;
            }
        }

        return $count;
    }

    /**
     * List all backups with metadata
     */
    public function listBackups(): array
    {
        $this->ensureBackupDir();
        $backups = [];

        $files = Storage::disk('local')->files(self::BACKUP_DIR);
        foreach ($files as $file) {
            if (str_ends_with($file, '.zip')) {
                $backups[] = [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'created_at' => Storage::disk('local')->lastModified($file),
                ];
            }
        }

        // Sort by creation time descending
        usort($backups, fn($a, $b) => $b['created_at'] <=> $a['created_at']);

        return $backups;
    }

    /**
     * Get backup metadata
     */
    public function getBackupMetadata(string $filename): ?array
    {
        $filepath = storage_path("app/" . self::BACKUP_DIR . "/{$filename}");

        if (!file_exists($filepath)) {
            return null;
        }

        try {
            $zip = new ZipArchive();
            if ($zip->open($filepath) !== true) {
                return null;
            }

            $metadata = $zip->getFromName('metadata.json');
            $zip->close();

            return $metadata ? json_decode($metadata, true) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Download backup file
     */
    public function getBackupPath(string $filename): ?string
    {
        $filepath = storage_path("app/" . self::BACKUP_DIR . "/{$filename}");

        if (file_exists($filepath) && str_ends_with($filename, '.zip')) {
            return $filepath;
        }

        return null;
    }

    /**
     * Delete backup file
     */
    public function deleteBackup(string $filename): bool
    {
        if (!str_ends_with($filename, '.zip') || str_contains($filename, '..')) {
            return false;
        }

        try {
            Storage::disk('local')->delete(self::BACKUP_DIR . "/{$filename}");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ensure backup directory exists
     */
    private function ensureBackupDir(): void
    {
        $dir = storage_path("app/" . self::BACKUP_DIR);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * Log backup operation
     */
    private function logBackup(string $action, array $data): void
    {
        $logFile = storage_path("logs/backups.log");
        $entry = [
            'timestamp' => now()->toIso8601String(),
            'action' => $action,
            'user_id' => auth()->id(),
            'data' => $data,
        ];
        file_put_contents($logFile, json_encode($entry) . PHP_EOL, FILE_APPEND);
    }
}
