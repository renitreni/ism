<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display the backup & restore page.
     */
    public function index()
    {
        $categories = $this->backupService->getCategories();
        $backupFiles = $this->backupService->listBackupFiles();
        $history = $this->backupService->getHistory();

        return view('backup', compact('categories', 'backupFiles', 'history'));
    }

    /**
     * Export selected categories as JSON backup.
     */
    public function exportCategories(Request $request)
    {
        $request->validate([
            'categories' => 'required|array|min:1',
            'categories.*' => 'string',
        ]);

        try {
            $selectedCategories = $request->input('categories');
            $allCategories = array_keys($this->backupService->getCategories());

            // Validate that all requested categories exist
            foreach ($selectedCategories as $cat) {
                if (!in_array($cat, $allCategories)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid category: {$cat}",
                    ], 422);
                }
            }

            $data = [
                'meta' => [
                    'type' => 'multi_category',
                    'categories' => $selectedCategories,
                    'label' => 'Selected Categories Backup',
                    'created_at' => now()->toIso8601String(),
                    'app' => 'ISM Backup System',
                    'version' => '1.0',
                ],
                'tables' => [],
            ];

            foreach ($selectedCategories as $cat) {
                $catData = $this->backupService->exportCategory($cat);
                foreach ($catData['tables'] as $tableName => $records) {
                    if (isset($data['tables'][$tableName])) {
                        $existing = collect($data['tables'][$tableName]);
                        $new = collect($records);
                        $data['tables'][$tableName] = $existing->merge($new)->unique('id')->values()->toArray();
                    } else {
                        $data['tables'][$tableName] = $records;
                    }
                }
            }

            $path = $this->backupService->saveToFile($data);
            $label = implode(', ', $selectedCategories);
            $this->backupService->logBackup($label, 'success', $path);

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully.',
                'filename' => basename($path),
            ]);
        } catch (\Exception $e) {
            $this->backupService->logBackup(
                implode(', ', $request->input('categories', [])),
                'failed',
                null,
                $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export full database backup.
     */
    public function exportAll()
    {
        // Unlimited time & enough memory for very large exports (600MB+)
        set_time_limit(0);
        ini_set('memory_limit', '1G');

        try {
            $path = $this->backupService->exportAllToFile();
            $this->backupService->logBackup('full', 'success', $path);

            return response()->json([
                'success' => true,
                'message' => 'Full backup created successfully.',
                'filename' => basename($path),
            ]);
        } catch (\Exception $e) {
            $this->backupService->logBackup('full', 'failed', null, $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Full backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a backup file.
     */
    public function download(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = basename($request->input('filename'));
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found.',
            ], 404);
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Delete a backup file.
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = basename($request->input('filename'));
        $path = 'backups/' . $filename;

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found.',
            ], 404);
        }

        \Illuminate\Support\Facades\Storage::disk('local')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'Backup file deleted.',
        ]);
    }

    /**
     * Return backup files list as JSON (for refreshing UI).
     */
    public function files()
    {
        return response()->json([
            'success' => true,
            'files' => $this->backupService->listBackupFiles(),
        ]);
    }

    /**
     * Return backup history as JSON.
     */
    public function history()
    {
        return response()->json([
            'success' => true,
            'history' => $this->backupService->getHistory(),
        ]);
    }
}
