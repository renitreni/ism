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
            'backup_file' => 'required|file|max:102400', // 100MB max
            'restore_type' => 'required|string',
        ]);

        try {
            $file = $request->file('backup_file');
            $contents = file_get_contents($file->getRealPath());
            $backupData = json_decode($contents, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON file. Please upload a valid backup file.',
                ], 422);
            }

            // Validate backup structure
            if (!isset($backupData['meta']) || !isset($backupData['tables'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid backup file structure. Missing meta or tables data.',
                ], 422);
            }

            $restoreType = $request->input('restore_type');

            if ($restoreType === 'full') {
                $result = $this->backupService->restoreAll($backupData);
                $this->backupService->logRestore('full', 'success', 'Full restore completed. Tables: ' . implode(', ', array_keys($result['restored'])));
            } else {
                // Validate category
                $allCategories = array_keys($this->backupService->getCategories());
                if (!in_array($restoreType, $allCategories)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid restore category: {$restoreType}",
                    ], 422);
                }

                $result = $this->backupService->restoreCategory($restoreType, $backupData);
                $this->backupService->logRestore($restoreType, 'success', 'Category restore completed. Tables: ' . implode(', ', array_keys($result['restored'])));
            }

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

        try {
            $filename = basename($request->input('filename'));
            $path = 'backups/' . $filename;

            if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found on server.',
                ], 404);
            }

            $contents = \Illuminate\Support\Facades\Storage::disk('local')->get($path);
            $backupData = json_decode($contents, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file contains invalid JSON.',
                ], 422);
            }

            if (!isset($backupData['meta']) || !isset($backupData['tables'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid backup file structure.',
                ], 422);
            }

            $restoreType = $request->input('restore_type');

            if ($restoreType === 'full') {
                $result = $this->backupService->restoreAll($backupData);
                $this->backupService->logRestore('full', 'success', "Restored from file: {$filename}");
            } else {
                $allCategories = array_keys($this->backupService->getCategories());
                if (!in_array($restoreType, $allCategories)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid restore category: {$restoreType}",
                    ], 422);
                }

                $result = $this->backupService->restoreCategory($restoreType, $backupData);
                $this->backupService->logRestore($restoreType, 'success', "Restored {$restoreType} from file: {$filename}");
            }

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
