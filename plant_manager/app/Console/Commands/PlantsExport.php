<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class PlantsExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plants:export
                            {--include-photos : Include photos in the backup}
                            {--format=json : Export format (json only for now)}
                            {--path= : Custom path for backup file}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Export all plants data to a ZIP archive with JSON and optional photos';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->info('Starting plants export...');

            $service = new BackupService();
            $options = [
                'include_photos' => $this->option('include-photos'),
                'compress' => true,
            ];

            $filename = $service->export($options);

            $backupPath = storage_path("app/backups/{$filename}");
            $size = filesize($backupPath);
            $sizeHuman = $this->formatBytes($size);

            $this->info("✓ Export completed successfully!");
            $this->line("  Filename: <fg=green>{$filename}</>");
            $this->line("  Size: <fg=yellow>{$sizeHuman}</>");
            $this->line("  Location: <fg=cyan>storage/app/backups/</>");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Export failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Format bytes to human-readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
