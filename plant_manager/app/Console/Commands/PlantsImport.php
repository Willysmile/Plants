<?php

namespace App\Console\Commands;

use App\Services\ImportService;
use Illuminate\Console\Command;

class PlantsImport extends Command
{
    protected $signature = 'plants:import 
        {backup : Path to backup ZIP file}
        {--mode=MERGE : Import mode (FRESH/MERGE/REPLACE)}
        {--dry-run : Preview what would be imported}';

    protected $description = 'Import plants from a backup ZIP file';

    public function handle(): int
    {
        $backupPath = $this->argument('backup');
        $mode = $this->option('mode');
        $dryRun = $this->option('dry-run');

        if (!file_exists($backupPath)) {
            $this->error("Backup file not found: {$backupPath}");
            return 1;
        }

        if (!in_array($mode, ['FRESH', 'MERGE', 'REPLACE'])) {
            $this->error("Invalid mode. Use: FRESH, MERGE, or REPLACE");
            return 1;
        }

        $this->info("Starting import from: " . basename($backupPath));
        $this->info("Mode: {$mode}");
        if ($dryRun) {
            $this->warn("DRY-RUN MODE: No data will be modified");
        }

        try {
            $importService = new ImportService();
            $result = $importService->import($backupPath, [
                'mode' => $mode,
                'dry_run' => $dryRun,
            ]);

            // Display results
            $this->displayResults($result);

            if ($result['status'] === 'failed') {
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }
    }

    private function displayResults(array $result): void
    {
        $this->newLine();

        // Status
        match($result['status']) {
            'completed' => $this->info('✓ Import completed successfully!'),
            'dry-run-completed' => $this->info('✓ Dry-run completed! (No data modified)'),
            'failed' => $this->error('✗ Import failed'),
            default => $this->line("Status: {$result['status']}")
        };

        $this->newLine();

        // Validation
        if (!empty($result['validation'])) {
            $this->line('<fg=cyan>Backup Validation:</fg=cyan>');
            $this->table(['Key', 'Value'], [
                ['Version', $result['validation']['version'] ?? 'unknown'],
                ['Plants', $result['validation']['plants_count'] ?? 0],
                ['Tags', ($result['validation']['has_tags'] ?? false) ? '✓' : '✗'],
                ['Histories', ($result['validation']['has_histories'] ?? false) ? '✓' : '✗'],
                ['Photos', $result['validation']['photos_count'] ?? 0],
            ]);
            $this->newLine();
        }

        // Counts
        if (!empty($result['counts'])) {
            $this->line('<fg=cyan>Import Counts:</fg=cyan>');
            $this->table(['Type', 'Count'], [
                ['Plants', $result['counts']['plants_imported'] ?? 0],
                ['Photos', $result['counts']['photos_imported'] ?? 0],
                ['Tags', $result['counts']['tags_synced'] ?? 0],
                ['Histories', $result['counts']['histories_imported'] ?? 0],
            ]);
            $this->newLine();
        }

        // Warnings
        if (!empty($result['warnings'])) {
            $this->line('<fg=yellow>Warnings:</fg=yellow>');
            foreach ($result['warnings'] as $warning) {
                $this->line("  ⚠ {$warning}");
            }
            $this->newLine();
        }

        // Errors
        if (!empty($result['errors'])) {
            $this->line('<fg=red>Errors:</fg=red>');
            foreach ($result['errors'] as $error) {
                $this->line("  ✗ {$error}");
            }
            $this->newLine();
        }
    }
}
