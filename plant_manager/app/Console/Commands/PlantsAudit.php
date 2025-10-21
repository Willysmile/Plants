<?php

namespace App\Console\Commands;

use App\Services\ResetService;
use Illuminate\Console\Command;

class PlantsAudit extends Command
{
    protected $signature = 'plants:audit 
        {--action= : Filter by action (reset, recover, purge)}
        {--limit=50 : Number of logs to display}';

    protected $description = 'View audit logs for plants operations';

    public function handle(): int
    {
        $action = $this->option('action');
        $limit = (int) $this->option('limit');

        $this->info('Loading audit logs...');

        try {
            $resetService = new ResetService();
            $result = $resetService->getAuditLogs([
                'action' => $action,
                'limit' => $limit,
            ]);

            if ($result['total'] === 0) {
                $this->info('No audit logs found.');
                return 0;
            }

            $this->line("<fg=cyan>Showing {$result['total']} audit logs:</fg=cyan>");
            $this->newLine();

            $tableData = [];
            foreach ($result['logs'] as $log) {
                $tableData[] = [
                    $log['id'],
                    $log['action'],
                    $log['model'] ?? '—',
                    $log['user'],
                    $log['reason'] ?? '—',
                    $log['created_at'],
                ];
            }

            $this->table(
                ['ID', 'Action', 'Model', 'User', 'Reason', 'Created'],
                $tableData
            );

            return 0;

        } catch (\Exception $e) {
            $this->error("Failed to load audit logs: " . $e->getMessage());
            return 1;
        }
    }
}
