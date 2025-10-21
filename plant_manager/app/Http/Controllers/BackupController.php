<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    protected BackupService $backupService;
    protected ImportService $importService;

    public function __construct(BackupService $backupService, ImportService $importService)
    {
        $this->backupService = $backupService;
        $this->importService = $importService;
    }

    /**
     * Show backups page
     */
    public function index()
    {
        $backups = $this->backupService->listBackups();
        
        // Enhance with metadata
        $backups = array_map(function($backup) {
            $metadata = $this->backupService->getBackupMetadata($backup['filename']);
            return array_merge($backup, [
                'metadata' => $metadata,
                'size_human' => $this->formatBytes($backup['size']),
                'created_at_human' => \Carbon\Carbon::createFromTimestamp($backup['created_at'])->diffForHumans(),
            ]);
        }, $backups);

        return view('settings.backups.index', compact('backups'));
    }

    /**
     * Trigger export
     */
    public function export(Request $request)
    {
        try {
            $filename = $this->backupService->export([
                'include_photos' => $request->boolean('include_photos', true),
                'compress' => true,
            ]);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'message' => 'Export completed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $filepath = $this->backupService->getBackupPath($filename);

        if (!$filepath) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filepath, $filename);
    }

    /**
     * Delete backup file
     */
    public function delete($filename)
    {
        if (!$this->backupService->deleteBackup($filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully',
        ]);
    }

    /**
     * Get import preview (dry-run)
     */
    public function importPreview(Request $request)
    {
        $request->validate([
            'backup' => 'required|string',
            'mode' => 'required|in:FRESH,MERGE,REPLACE',
        ]);

        try {
            $backupDir = storage_path('app/' . BackupService::BACKUP_DIR);
            $filepath = $backupDir . '/' . $request->input('backup');

            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }

            $result = $this->importService->import($filepath, [
                'mode' => $request->input('mode'),
                'dry_run' => true,
            ]);

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Perform import
     */
    public function import(Request $request)
    {
        $request->validate([
            'backup' => 'required|string',
            'mode' => 'required|in:FRESH,MERGE,REPLACE',
            'confirmed' => 'required|boolean',
        ]);

        if (!$request->boolean('confirmed')) {
            return response()->json([
                'success' => false,
                'message' => 'Import must be confirmed',
            ], 400);
        }

        try {
            $backupDir = storage_path('app/' . BackupService::BACKUP_DIR);
            $filepath = $backupDir . '/' . $request->input('backup');

            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }

            $result = $this->importService->import($filepath, [
                'mode' => $request->input('mode'),
                'dry_run' => false,
            ]);

            if ($result['status'] === 'failed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed',
                    'errors' => $result['errors'],
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Import completed successfully',
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get backup info
     */
    public function getBackupInfo(Request $request)
    {
        $request->validate([
            'backup' => 'required|string',
        ]);

        try {
            $backupDir = storage_path('app/' . BackupService::BACKUP_DIR);
            $filepath = $backupDir . '/' . $request->input('backup');

            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found',
                ], 404);
            }

            $info = $this->importService->getBackupInfo($request->input('backup'));

            return response()->json([
                'success' => true,
                'info' => $info,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to read backup: ' . $e->getMessage(),
            ], 500);
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
