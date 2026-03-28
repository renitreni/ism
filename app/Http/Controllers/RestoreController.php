<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Restore data from an uploaded backup file.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:2097152', // 2GB max
            'restore_type' => 'required|string',
        ]);

        // Allow unlimited time and memory for large restore operations (800MB+ files)
        set_time_limit(0);
        ini_set('memory_limit', '2G');

        $tempPath = null;
        try {
            $file = $request->file('backup_file');
            $tempPath = $file->getRealPath();

            // Validate JSON structure without loading entire file
            $handle = fopen($tempPath, 'r');
            $headerChunk = fread($handle, 100);
            fclose($handle);

            if (strpos($headerChunk, '"meta"') === false || strpos($headerChunk, '"tables"') === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid backup file structure. Missing meta or tables data.',
                ], 422);
            }

            $restoreType = $request->input('restore_type');

            // Validate category
            if ($restoreType !== 'full') {
                $allCategories = array_keys($this->backupService->getCategories());
                if (!in_array($restoreType, $allCategories)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid restore category: {$restoreType}",
                    ], 422);
                }
            }

            // Use streaming restore for uploaded files
            $result = $this->backupService->restoreFromFile($tempPath, $restoreType);
            $message = $restoreType === 'full'
                ? 'Full database restored successfully from uploaded file'
                : "Category '{$restoreType}' restored successfully from uploaded file";

            $this->backupService->logRestore($restoreType, 'success', $message);

            return response()->json([
                'success' => true,
                'message' => 'Data restored successfully.',
                'details' => $result['restored'],
            ]);
        } catch (\Exception $e) {
            $this->backupService->logRestore(
                $request->input('restore_type', 'unknown'),
                'failed',
                $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore from an existing backup file on the server.
     */
    public function restoreFromFile(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'restore_type' => 'required|string',
        ]);

        // Allow unlimited time and memory for large restore operations (800MB+ files)
        set_time_limit(0);
        ini_set('memory_limit', '2G');

        try {
            $filename = basename($request->input('filename'));
            $path = storage_path('app/backups/' . $filename);

            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found on server.',
                ], 404);
            }

            $restoreType = $request->input('restore_type');

            // Validate category
            if ($restoreType !== 'full') {
                $allCategories = array_keys($this->backupService->getCategories());
                if (!in_array($restoreType, $allCategories)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid restore category: {$restoreType}",
                    ], 422);
                }
            }

            // Use streaming restore for large files
            $result = $this->backupService->restoreFromFile($path, $restoreType);
            $message = $restoreType === 'full'
                ? "Full database restored successfully from {$filename}"
                : "Category '{$restoreType}' restored successfully from {$filename}";

            $this->backupService->logRestore($restoreType, 'success', $message);

            return response()->json([
                'success' => true,
                'message' => 'Data restored successfully.',
                'details' => $result['restored'],
            ]);
        } catch (\Exception $e) {
            $this->backupService->logRestore(
                $request->input('restore_type', 'unknown'),
                'failed',
                $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

