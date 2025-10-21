<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\PlantHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use ZipArchive;

class ImportService
{
    const IMPORT_MODES = ['FRESH', 'MERGE', 'REPLACE'];

    /**
     * Import plants from backup ZIP file
     * 
     * @param string $filepath Path to backup ZIP file
     * @param array $options Options: mode (FRESH/MERGE/REPLACE), dry_run (bool)
     * @return array Result with status, counts, warnings, errors
     */
    public function import(string $filepath, array $options = []): array
    {
        $mode = $options['mode'] ?? 'MERGE';
        $dryRun = $options['dry_run'] ?? true;

        if (!in_array($mode, self::IMPORT_MODES)) {
            throw new \InvalidArgumentException("Invalid import mode: {$mode}. Use: " . implode(', ', self::IMPORT_MODES));
        }

        if (!file_exists($filepath)) {
            throw new \RuntimeException("Backup file not found: {$filepath}");
        }

        $result = [
            'status' => 'pending',
            'mode' => $mode,
            'dry_run' => $dryRun,
            'counts' => [
                'plants_imported' => 0,
                'photos_imported' => 0,
                'categories_synced' => 0,
                'tags_synced' => 0,
                'histories_imported' => 0,
            ],
            'warnings' => [],
            'errors' => [],
            'validation' => [],
        ];

        try {
            // Extract and validate backup
            $backupData = $this->extractBackup($filepath);
            $result['validation'] = $backupData['validation'];

            if (!empty($backupData['errors'])) {
                $result['status'] = 'failed';
                $result['errors'] = array_merge($result['errors'], $backupData['errors']);
                return $result;
            }

            // Perform import based on mode
            if (!$dryRun) {
                DB::transaction(function () use ($backupData, $mode, &$result) {
                    if ($mode === 'FRESH') {
                        $this->importFresh($backupData, $result);
                    } elseif ($mode === 'MERGE') {
                        $this->importMerge($backupData, $result);
                    } elseif ($mode === 'REPLACE') {
                        $this->importReplace($backupData, $result);
                    }
                });
                
                $result['status'] = 'completed';
            } else {
                // In dry-run mode, just calculate what would be imported
                $result['status'] = 'dry-run-completed';
                $this->calculateImportStats($backupData, $mode, $result);
            }

        } catch (\Exception $e) {
            $result['status'] = 'failed';
            $result['errors'][] = "Import failed: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Extract and validate backup ZIP file
     */
    private function extractBackup(string $filepath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filepath) !== true) {
            return [
                'errors' => ['Failed to open backup ZIP file'],
                'validation' => [],
            ];
        }

        $result = [
            'data' => [],
            'photos' => [],
            'errors' => [],
            'validation' => [],
        ];

        try {
            // Extract backup.json - locateName returns index (>=0) if found, false if not
            $backupIndex = $zip->locateName('backup.json');
            if ($backupIndex === false) {
                $result['errors'][] = 'backup.json not found in archive';
                return $result;
            }

            $jsonContent = $zip->getFromName('backup.json');
            $data = json_decode($jsonContent, true);

            if (!$data) {
                $result['errors'][] = 'Invalid JSON in backup.json';
                return $result;
            }

            // Validate format version - check metadata.json first, then backup.json
            $version = $data['version'] ?? null;
            
            if (!$version && $zip->locateName('metadata.json') !== false) {
                $metadataJson = $zip->getFromName('metadata.json');
                $metadata = json_decode($metadataJson, true);
                $version = $metadata['export_format'] ?? null;
            }

            // Accept if version is plants-backup-v1 or if no version specified (backward compatibility)
            if ($version && $version !== 'plants-backup-v1') {
                $result['errors'][] = 'Unsupported backup version: ' . $version;
                return $result;
            }

            // Validate structure
            $validation = $this->validateBackupStructure($data);
            $result['validation'] = $validation;

            if (!empty($validation['errors'])) {
                $result['errors'] = array_merge($result['errors'], $validation['errors']);
                return $result;
            }

            $result['data'] = $data;

            // Extract photos if present
            $photosDir = 'photos/';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filename = $stat['name'];

                if (strpos($filename, $photosDir) === 0 && $filename !== $photosDir) {
                    $photoContent = $zip->getFromIndex($i);
                    $result['photos'][$filename] = $photoContent;
                }
            }

            $result['validation']['photos_count'] = count($result['photos']);

        } finally {
            $zip->close();
        }

        return $result;
    }

    /**
     * Validate backup structure
     */
    private function validateBackupStructure(array $data): array
    {
        $validation = [
            'version' => $data['version'] ?? null,
            'has_plants' => !empty($data['plants']),
            'plants_count' => count($data['plants'] ?? []),
            'has_tags' => !empty($data['tags']),
            'has_histories' => !empty($data['plant_histories']),
            'errors' => [],
        ];

        // Validate plants
        foreach ($data['plants'] ?? [] as $plant) {
            if (empty($plant['name'])) {
                $validation['errors'][] = 'Plant without name found';
                break;
            }
        }

        return $validation;
    }

    /**
     * FRESH mode: Clear all data and import backup
     */
    private function importFresh(array $backupData, array &$result): void
    {
        // Clear existing data (including soft-deleted)
        // Delete in correct order to respect foreign key relationships
        PlantHistory::query()->forceDelete();  // Force delete plant histories first (includes soft-deleted)
        Photo::query()->forceDelete();          // Force delete photos (includes soft-deleted)
        Plant::query()->forceDelete();          // Force delete plants (includes soft-deleted)
        Tag::query()->forceDelete();            // Force delete tags (includes soft-deleted)

        // Import fresh
        $this->importData($backupData, $result);
    }

    /**
     * MERGE mode: Keep existing data, add/update from backup
     */
    private function importMerge(array $backupData, array &$result): void
    {
        $this->importData($backupData, $result, 'merge');
    }

    /**
     * REPLACE mode: Replace data by name/unique key
     */
    private function importReplace(array $backupData, array &$result): void
    {
        $this->importData($backupData, $result, 'replace');
    }

    /**
     * Import data from backup
     * Adapted for actual backup format: plants, tags, plant_histories, photos
     */
    private function importData(array $backupData, array &$result, string $strategy = 'fresh'): void
    {
        $data = $backupData['data'];
        $photoMapping = []; // Map old photo IDs to new IDs

        // Import/sync tags
        $tagMapping = [];
        foreach ($data['tags'] ?? [] as $tagData) {
            $oldId = $tagData['id'];
            $tagName = $tagData['name'];
            
            if ($strategy === 'fresh') {
                $tag = Tag::create([
                    'name' => $tagName,
                    'category' => $tagData['category'] ?? null,
                ]);
            } else {
                // In MERGE/REPLACE mode, use firstOrCreate to avoid duplicate key errors
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['category' => $tagData['category'] ?? null]
                );
                // Update category if it was empty
                if (!$tag->category && !empty($tagData['category'])) {
                    $tag->update(['category' => $tagData['category']]);
                }
            }
            
            $tagMapping[$oldId] = $tag->id;
            $result['counts']['tags_synced']++;
        }

        // Import plants
        $plantMapping = [];
        foreach ($data['plants'] ?? [] as $plantData) {
            $oldId = $plantData['id'];
            
            // Prepare plant data (remove tags array for now)
            $plantCreateData = Arr::except($plantData, ['id', 'created_at', 'updated_at', 'tags', 'histories']);

            if ($strategy === 'fresh') {
                $plant = Plant::create($plantCreateData);
            } else {
                $reference = $plantData['reference'] ?? 'ref-' . $oldId;
                \Log::info('Upserting plant', [
                    'reference' => $reference,
                    'name' => $plantData['name'] ?? 'unknown',
                ]);
                
                // In MERGE mode, check if plant exists (including soft-deleted)
                // If it's soft-deleted, restore it; otherwise update it
                $plant = Plant::withTrashed()->firstOrCreate(
                    ['reference' => $reference],
                    $plantCreateData
                );
                
                // If the plant was soft-deleted, restore it
                if ($plant->trashed()) {
                    $plant->restore();
                }
                
                // Update the plant data
                $plant->update($plantCreateData);
            }

            $plantMapping[$oldId] = $plant->id;
            $result['counts']['plants_imported']++;

            // Attach tags by name (from backup)
            $tags = [];
            foreach ($plantData['tags'] ?? [] as $tagName) {
                $tag = Tag::where('name', $tagName)->first();
                if ($tag) {
                    $tags[] = $tag->id;
                }
            }
            if (!empty($tags)) {
                $plant->tags()->sync($tags);
            }
        }

        // Import photos (from backup.json metadata)
        $disk = Storage::disk('public');
        foreach ($data['photos'] ?? [] as $photoData) {
            $oldId = $photoData['id'];
            $oldPlantId = $photoData['plant_id'];
            $filename = $photoData['filename'];

            if (!isset($plantMapping[$oldPlantId])) {
                $result['warnings'][] = "Photo {$filename} skipped: plant not found";
                continue;
            }

            // Store photo from backup archive
            $photoPath = "photos/" . basename($filename);
            if (isset($backupData['photos'][$photoPath])) {
                $newFilename = Str::uuid() . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                $disk->put('plants/' . $newFilename, $backupData['photos'][$photoPath]);

                $photo = Photo::create([
                    'plant_id' => $plantMapping[$oldPlantId],
                    'filename' => $newFilename,
                    'description' => $photoData['description'] ?? null,
                    'is_main' => $photoData['is_main'] ?? false,
                ]);

                $photoMapping[$oldId] = $photo->id;
                $result['counts']['photos_imported']++;
            } else {
                $result['warnings'][] = "Photo file {$filename} not found in archive";
            }
        }

        // Import plant histories
        foreach ($data['plant_histories'] ?? [] as $historyData) {
            if (!isset($plantMapping[$historyData['plant_id']])) {
                continue;
            }

            $historyData['plant_id'] = $plantMapping[$historyData['plant_id']];

            PlantHistory::create(Arr::except($historyData, ['id', 'created_at', 'updated_at']));
            $result['counts']['histories_imported']++;
        }
    }

    /**
     * Calculate import statistics for dry-run mode
     */
    private function calculateImportStats(array $backupData, string $mode, array &$result): void
    {
        $data = $backupData['data'];

        $result['counts']['categories_synced'] = count($data['categories'] ?? []);
        $result['counts']['tags_synced'] = count($data['tags'] ?? []);
        $result['counts']['plants_imported'] = count($data['plants'] ?? []);
        $result['counts']['photos_imported'] = count($backupData['photos'] ?? []);
        $result['counts']['histories_imported'] = count($data['plant_histories'] ?? []);

        // Add mode-specific warnings
        if ($mode === 'FRESH') {
            $result['warnings'][] = 'FRESH mode: All existing data will be deleted and replaced';
            $existingPlants = Plant::count();
            if ($existingPlants > 0) {
                $result['warnings'][] = "Warning: {$existingPlants} existing plants will be permanently deleted";
            }
        } elseif ($mode === 'REPLACE') {
            $result['warnings'][] = 'REPLACE mode: Existing plants with same reference will be updated';
        }
    }

    /**
     * List available backups
     */
    public function listBackups(): array
    {
        $backupDir = storage_path('app/' . BackupService::BACKUP_DIR);
        
        if (!is_dir($backupDir)) {
            return [];
        }

        $backups = [];
        foreach (scandir($backupDir) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $filepath = $backupDir . '/' . $file;
                $backups[] = [
                    'filename' => $file,
                    'size' => filesize($filepath),
                    'created_at' => filemtime($filepath),
                ];
            }
        }

        // Sort by date descending
        usort($backups, fn($a, $b) => $b['created_at'] <=> $a['created_at']);
        
        return $backups;
    }

    /**
     * Get backup info
     */
    public function getBackupInfo(string $filename): ?array
    {
        $backupDir = storage_path('app/' . BackupService::BACKUP_DIR);
        $filepath = $backupDir . '/' . $filename;

        if (!file_exists($filepath)) {
            return null;
        }

        $zip = new ZipArchive();
        if ($zip->open($filepath) !== true) {
            return null;
        }

        $info = [
            'filename' => $filename,
            'size' => filesize($filepath),
            'created_at' => filemtime($filepath),
            'validation' => [],
        ];

        try {
            if ($zip->locateName('backup.json')) {
                $jsonContent = $zip->getFromName('backup.json');
                $data = json_decode($jsonContent, true);

                $info['validation'] = [
                    'version' => $data['version'] ?? null,
                    'plants_count' => count($data['plants'] ?? []),
                    'categories_count' => count($data['categories'] ?? []),
                    'tags_count' => count($data['tags'] ?? []),
                    'histories_count' => count($data['plant_histories'] ?? []),
                    'photos_count' => count($data['plants_photos'] ?? []),
                ];
            }
        } finally {
            $zip->close();
        }

        return $info;
    }
}
