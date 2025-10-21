<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Photo;
use App\Models\PlantHistory;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResetService
{
    const RECOVERY_WINDOW_DAYS = 30;

    /**
     * Reset all plants data
     * 
     * @param array $options Options: reason, create_backup
     * @return array Result with status, counts, recovery_deadline
     */
    public function reset(array $options = []): array
    {
        $reason = $options['reason'] ?? 'Administrative reset';
        $createBackup = $options['create_backup'] ?? false;
        $dryRun = $options['dry_run'] ?? false;

        $result = [
            'status' => 'pending',
            'dry_run' => $dryRun,
            'counts' => [
                'plants_deleted' => 0,
                'photos_deleted' => 0,
                'histories_deleted' => 0,
            ],
            'recovery_deadline' => null,
            'backup_filename' => null,
            'errors' => [],
        ];

        try {
            // Create backup if requested
            if ($createBackup) {
                $backupService = new BackupService();
                $filename = $backupService->export([
                    'include_photos' => true,
                    'compress' => true,
                ]);
                $result['backup_filename'] = $filename;
            }

            if (!$dryRun) {
                // Perform actual reset in transaction
                DB::transaction(function () use ($reason, &$result) {
                    $this->performReset($reason, $result);
                });

                $result['status'] = 'completed';
                $result['recovery_deadline'] = now()->addDays(self::RECOVERY_WINDOW_DAYS)->toIso8601String();
            } else {
                // Calculate what would be deleted
                $result['counts']['plants_deleted'] = Plant::count();
                $result['counts']['photos_deleted'] = Photo::count();
                $result['counts']['histories_deleted'] = PlantHistory::count();
                $result['status'] = 'dry-run-completed';
            }

        } catch (\Exception $e) {
            $result['status'] = 'failed';
            $result['errors'][] = "Reset failed: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Perform the actual reset (soft-delete)
     */
    private function performReset(string $reason, array &$result): void
    {
        $userId = Auth::id();
        $recoveryDeadline = now()->addDays(self::RECOVERY_WINDOW_DAYS);

        // Delete plants (soft-delete)
        $plants = Plant::all();
        $result['counts']['plants_deleted'] = 0;

        foreach ($plants as $plant) {
            // Log before deletion
            AuditLog::log([
                'action' => 'reset',
                'model_type' => 'Plant',
                'model_id' => $plant->id,
                'old_values' => $plant->toArray(),
                'reason' => $reason,
            ]);

            // Soft delete
            $plant->update([
                'deleted_by_user_id' => $userId,
                'deletion_reason' => $reason,
                'recovery_deadline' => $recoveryDeadline,
            ]);
            $plant->delete();
            $result['counts']['plants_deleted']++;
        }

        // Photos cascade delete with plants
        $photos = Photo::all();
        $result['counts']['photos_deleted'] = 0;

        foreach ($photos as $photo) {
            AuditLog::log([
                'action' => 'reset',
                'model_type' => 'Photo',
                'model_id' => $photo->id,
                'reason' => $reason,
            ]);
            $photo->delete();
            $result['counts']['photos_deleted']++;
        }

        // Histories cascade delete
        $histories = PlantHistory::all();
        $result['counts']['histories_deleted'] = 0;

        foreach ($histories as $history) {
            $history->delete();
            $result['counts']['histories_deleted']++;
        }

        // Log the reset action
        AuditLog::log([
            'action' => 'reset_completed',
            'reason' => $reason,
            'new_values' => [
                'plants_deleted' => $result['counts']['plants_deleted'],
                'photos_deleted' => $result['counts']['photos_deleted'],
                'histories_deleted' => $result['counts']['histories_deleted'],
                'recovery_deadline' => $recoveryDeadline->toIso8601String(),
            ],
        ]);
    }

    /**
     * Recover (restore) deleted plants within recovery window
     * 
     * @param array $options Options: plant_ids (array of IDs to recover)
     * @return array Result with status, counts
     */
    public function recover(array $options = []): array
    {
        $plantIds = $options['plant_ids'] ?? null;
        $reason = $options['reason'] ?? 'User recovery';

        $result = [
            'status' => 'pending',
            'counts' => [
                'plants_recovered' => 0,
                'expired_items' => 0,
            ],
            'errors' => [],
        ];

        try {
            DB::transaction(function () use ($plantIds, $reason, &$result) {
                // Find deleted plants within recovery window
                $query = Plant::onlyTrashed()
                    ->where('recovery_deadline', '>', now());

                if ($plantIds) {
                    $query->whereIn('id', $plantIds);
                }

                $plants = $query->get();

                foreach ($plants as $plant) {
                    // Log recovery
                    AuditLog::log([
                        'action' => 'recover',
                        'model_type' => 'Plant',
                        'model_id' => $plant->id,
                        'old_values' => [
                            'deleted_at' => $plant->deleted_at,
                            'deletion_reason' => $plant->deletion_reason,
                        ],
                        'reason' => $reason,
                    ]);

                    // Restore
                    $plant->restore();
                    $plant->update([
                        'deleted_by_user_id' => null,
                        'deletion_reason' => null,
                        'recovery_deadline' => null,
                    ]);

                    $result['counts']['plants_recovered']++;
                }

                // Count expired items
                $expired = Plant::onlyTrashed()
                    ->where('recovery_deadline', '<=', now())
                    ->count();
                $result['counts']['expired_items'] = $expired;
            });

            $result['status'] = 'completed';

        } catch (\Exception $e) {
            $result['status'] = 'failed';
            $result['errors'][] = "Recovery failed: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Permanently delete expired items (after recovery window)
     */
    public function purgeExpired(): array
    {
        $result = [
            'status' => 'pending',
            'counts' => [
                'plants_purged' => 0,
                'photos_purged' => 0,
                'histories_purged' => 0,
            ],
            'errors' => [],
        ];

        try {
            DB::transaction(function () use (&$result) {
                // Find and permanently delete expired plants
                $plants = Plant::onlyTrashed()
                    ->where('recovery_deadline', '<=', now())
                    ->get();

                foreach ($plants as $plant) {
                    // Log permanent deletion
                    AuditLog::log([
                        'action' => 'purge',
                        'model_type' => 'Plant',
                        'model_id' => $plant->id,
                        'reason' => 'Recovery window expired',
                    ]);

                    $plant->forceDelete();
                    $result['counts']['plants_purged']++;
                }

                // Purge orphaned photos
                $photos = Photo::onlyTrashed()
                    ->where('deleted_at', '<=', now()->subDays(self::RECOVERY_WINDOW_DAYS))
                    ->get();

                foreach ($photos as $photo) {
                    $photo->forceDelete();
                    $result['counts']['photos_purged']++;
                }

                // Purge orphaned histories
                $histories = PlantHistory::onlyTrashed()
                    ->where('deleted_at', '<=', now()->subDays(self::RECOVERY_WINDOW_DAYS))
                    ->get();

                foreach ($histories as $history) {
                    $history->forceDelete();
                    $result['counts']['histories_purged']++;
                }
            });

            $result['status'] = 'completed';

        } catch (\Exception $e) {
            $result['status'] = 'failed';
            $result['errors'][] = "Purge failed: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Get deleted items within recovery window
     */
    public function getDeletedItems(): array
    {
        $plants = Plant::onlyTrashed()
            ->where('recovery_deadline', '>', now())
            ->select('id', 'name', 'reference', 'deleted_at', 'recovery_deadline', 'deletion_reason')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'reference' => $p->reference,
                'deleted_at' => $p->deleted_at->toIso8601String(),
                'recovery_deadline' => $p->recovery_deadline->toIso8601String(),
                'days_remaining' => $p->recovery_deadline->diffInDays(now()),
                'reason' => $p->deletion_reason,
            ])
            ->toArray();

        return [
            'items' => $plants,
            'count' => count($plants),
            'recovery_window_days' => self::RECOVERY_WINDOW_DAYS,
        ];
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs(array $options = []): array
    {
        $action = $options['action'] ?? null;
        $limit = $options['limit'] ?? 50;

        $query = AuditLog::with('user');

        if ($action) {
            $query->where('action', $action);
        }

        $logs = $query->latest()->limit($limit)->get()->map(fn($log) => [
            'id' => $log->id,
            'action' => $log->action,
            'model' => $log->model_type,
            'model_id' => $log->model_id,
            'user' => $log->user?->name ?? 'System',
            'reason' => $log->reason,
            'created_at' => $log->created_at->toIso8601String(),
        ])->toArray();

        return [
            'logs' => $logs,
            'total' => count($logs),
        ];
    }
}
