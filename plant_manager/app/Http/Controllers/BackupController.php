<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
        $this->middleware('auth');
        $this->middleware('admin'); // Add admin middleware
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
