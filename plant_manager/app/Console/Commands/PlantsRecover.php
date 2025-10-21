<?php

namespace App\Console\Commands;

use App\Services\ResetService;
use Illuminate\Console\Command;

class PlantsRecover extends Command
{
    protected $signature = 'plants:recover 
        {--ids= : Comma-separated plant IDs to recover}
        {--all : Recover all deleted plants}
        {--dry-run : Preview what would be recovered}';

    protected $description = 'Recover deleted plants (within 30-day window)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $ids = $this->option('ids');
        $recoverAll = $this->option('all');

        $this->info('Checking for recoverable items...');

        try {
            $resetService = new ResetService();
            
            // Show what can be recovered
            $deleted = $resetService->getDeletedItems();
            
            if ($deleted['count'] === 0) {
                $this->info('No deleted items found within recovery window.');
                return 0;
            }

            $this->line("<fg=cyan>Found {$deleted['count']} deleted items:</fg=cyan>");
            
            $tableData = [];
            foreach ($deleted['items'] as $item) {
                $tableData[] = [
                    $item['id'],
                    $item['name'],
                    $item['reference'],
                    $item['days_remaining'] . ' days',
                ];
            }
            
            $this->table(['ID', 'Name', 'Reference', 'Recovery Time'], $tableData);
            $this->newLine();

            // Determine which to recover
            $toRecover = [];
            if ($recoverAll) {
                $toRecover = array_column($deleted['items'], 'id');
            } elseif ($ids) {
                $toRecover = array_map('intval', explode(',', $ids));
            } else {
                $this->info('No recovery requested. Use --ids=ID or --all');
                return 0;
            }

            if (empty($toRecover)) {
                $this->info('No items to recover.');
                return 0;
            }

            if ($dryRun) {
                $this->warn("DRY-RUN: Would recover " . count($toRecover) . " items");
                return 0;
            }

            if (!$this->confirm("Recover " . count($toRecover) . " item(s)?")) {
                $this->info('Recovery cancelled.');
                return 1;
            }

            // Perform recovery
            $result = $resetService->recover([
                'plant_ids' => $toRecover,
                'reason' => 'User recovery',
            ]);

            $this->displayResults($result);

            if ($result['status'] === 'failed') {
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Recovery failed: " . $e->getMessage());
            return 1;
        }
    }

    private function displayResults(array $result): void
    {
        $this->newLine();

        match($result['status']) {
            'completed' => $this->info('✓ Recovery completed successfully!'),
            'failed' => $this->error('✗ Recovery failed'),
        };

        $this->newLine();

        if (!empty($result['counts'])) {
            $this->table(['Type', 'Count'], [
                ['Plants Recovered', $result['counts']['plants_recovered']],
                ['Expired Items', $result['counts']['expired_items']],
            ]);
            $this->newLine();
        }

        if (!empty($result['errors'])) {
            $this->line('<fg=red>Errors:</fg=red>');
            foreach ($result['errors'] as $error) {
                $this->line("  ✗ {$error}");
            }
            $this->newLine();
        }
    }
}
