<?php

namespace App\Console\Commands;

use App\Services\ResetService;
use Illuminate\Console\Command;

class PlantsReset extends Command
{
    protected $signature = 'plants:reset 
        {--backup : Create backup before reset}
        {--dry-run : Preview what would be deleted}
        {--reason=Administrative reset : Reason for reset}
        {--force : Skip confirmation}';

    protected $description = 'Reset all plants data (soft-delete with 30-day recovery)';

    public function handle(): int
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  WARNING: This will delete ALL plants, photos, and histories!');
            $this->warn('Recovery window: 30 days');
            
            if (!$this->confirm('Are you absolutely sure?')) {
                $this->info('Reset cancelled.');
                return 1;
            }
        }

        $reason = $this->option('reason');
        $createBackup = $this->option('backup');
        $dryRun = $this->option('dry-run');

        $this->info('Starting reset...');
        if ($dryRun) {
            $this->warn('DRY-RUN MODE: No data will be modified');
        }
        if ($createBackup) {
            $this->line('Creating backup before reset...');
        }

        try {
            $resetService = new ResetService();
            $result = $resetService->reset([
                'reason' => $reason,
                'create_backup' => $createBackup,
                'dry_run' => $dryRun,
            ]);

            $this->displayResults($result);

            if ($result['status'] === 'failed') {
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Reset failed: " . $e->getMessage());
            return 1;
        }
    }

    private function displayResults(array $result): void
    {
        $this->newLine();

        // Status
        match($result['status']) {
            'completed' => $this->info('✓ Reset completed successfully!'),
            'dry-run-completed' => $this->info('✓ Dry-run completed! (No data modified)'),
            'failed' => $this->error('✗ Reset failed'),
            default => $this->line("Status: {$result['status']}")
        };

        $this->newLine();

        // Backup info
        if ($result['backup_filename']) {
            $this->line("<fg=cyan>Backup created:</fg=cyan> {$result['backup_filename']}");
            $this->newLine();
        }

        // Counts
        if (!empty($result['counts'])) {
            $this->line('<fg=cyan>Deleted Items:</fg=cyan>');
            $this->table(['Type', 'Count'], [
                ['Plants', $result['counts']['plants_deleted']],
                ['Photos', $result['counts']['photos_deleted']],
                ['Histories', $result['counts']['histories_deleted']],
            ]);
            $this->newLine();
        }

        // Recovery deadline
        if ($result['recovery_deadline']) {
            $this->line("<fg=green>Recovery deadline: {$result['recovery_deadline']}</fg=green>");
            $this->line("Items can be recovered for 30 days using: <fg=yellow>php artisan plants:recover</fg=yellow>");
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
