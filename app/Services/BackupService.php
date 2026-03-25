<?php

namespace App\Services;

use App\BackupLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    /**
     * Category definitions with their tables and dependencies.
     * Order matters for restore: dependencies listed first.
     */
    protected $categories = [
        'purchase_orders' => [
            'label' => 'Purchase Orders (P.O)',
            'tables' => [
                'purchase_infos',
                'product_details:purchase_order',
                'summaries:purchase_order',
            ],
        ],
        'sales_orders' => [
            'label' => 'Sales Orders (S.O)',
            'tables' => [
                'sales_orders',
                'product_details:sales_order',
                'summaries:sales_order',
                'order_forms',
            ],
        ],
        'products_categories' => [
            'label' => 'Products & Categories',
            'tables' => [
                'categories',
                'products',
                'supplies',
                'supply_history',
                'galleries',
                'tags',
                'taggables',
            ],
        ],
        'vendors' => [
            'label' => 'Vendors',
            'tables' => [
                'vendors',
            ],
        ],
        'customers' => [
            'label' => 'Customers',
            'tables' => [
                'customers',
            ],
        ],
        'expenses' => [
            'label' => 'Expenses',
            'tables' => [
                'expenses',
            ],
        ],
        'job_orders' => [
            'label' => 'Job Orders',
            'tables' => [
                'job_orders',
                'job_order_statuses',
                'job_order_products',
            ],
        ],
        'product_returns' => [
            'label' => 'Product Returns',
            'tables' => [
                'product_returns',
                'return_statuses',
                'product_details:product_return',
            ],
        ],
        'system_settings' => [
            'label' => 'System Settings',
            'tables' => [
                'users',
                'preferences',
                'print_settings',
                'payment_methods',
                'price_lists',
                'audit_logs',
            ],
        ],
    ];

    /**
     * Full restore order respecting foreign key dependencies.
     */
    protected $fullRestoreOrder = [
        'categories',
        'products',
        'supplies',
        'supply_history',
        'galleries',
        'tags',
        'taggables',
        'vendors',
        'customers',
        'users',
        'preferences',
        'print_settings',
        'payment_methods',
        'price_lists',
        'purchase_infos',
        'sales_orders',
        'order_forms',
        'product_returns',
        'return_statuses',
        'expenses',
        'job_orders',
        'job_order_statuses',
        'job_order_products',
        'product_details',
        'summaries',
        'audit_logs',
    ];

    /**
     * Get available categories for the UI.
     */
    public function getCategories(): array
    {
        $result = [];
        foreach ($this->categories as $key => $cat) {
            $result[$key] = $cat['label'];
        }
        return $result;
    }

    /**
     * Export data for a single category.
     */
    public function exportCategory(string $type): array
    {
        if (!isset($this->categories[$type])) {
            throw new \InvalidArgumentException("Unknown backup category: {$type}");
        }

        $data = [
            'meta' => [
                'type' => 'category',
                'category' => $type,
                'label' => $this->categories[$type]['label'],
                'created_at' => Carbon::now()->toIso8601String(),
                'app' => 'ISM Backup System',
                'version' => '1.0',
            ],
            'tables' => [],
        ];

        foreach ($this->categories[$type]['tables'] as $tableDef) {
            $tableData = $this->fetchTableData($tableDef);
            $data['tables'][$tableData['table']] = $tableData['records'];
        }

        return $data;
    }

    /**
     * Export all data (full database backup).
     */
    public function exportAll(): array
    {
        $data = [
            'meta' => [
                'type' => 'full',
                'category' => 'all',
                'label' => 'Full Database Backup',
                'created_at' => Carbon::now()->toIso8601String(),
                'app' => 'ISM Backup System',
                'version' => '1.0',
            ],
            'tables' => [],
        ];

        // Export each category's tables
        foreach ($this->categories as $type => $category) {
            foreach ($category['tables'] as $tableDef) {
                $tableData = $this->fetchTableData($tableDef);
                $tableName = $tableData['table'];
                // For product_details and summaries, merge rather than overwrite
                if (isset($data['tables'][$tableName])) {
                    $existing = collect($data['tables'][$tableName]);
                    $new = collect($tableData['records']);
                    $merged = $existing->merge($new)->unique('id')->values()->toArray();
                    $data['tables'][$tableName] = $merged;
                } else {
                    $data['tables'][$tableName] = $tableData['records'];
                }
            }
        }

        return $data;
    }

    /**
     * Restore data for a single category from uploaded data.
     */
    public function restoreCategory(string $type, array $backupData): array
    {
        if (!isset($this->categories[$type])) {
            throw new \InvalidArgumentException("Unknown restore category: {$type}");
        }

        $tables = $backupData['tables'] ?? [];
        $restored = [];
        $errors = [];

        // Move FK check outside transaction to avoid scope issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            DB::beginTransaction();

            foreach ($this->categories[$type]['tables'] as $tableDef) {
                $parsed = $this->parseTableDef($tableDef);
                $tableName = $parsed['table'];
                $scope = $parsed['scope'];

                if (!isset($tables[$tableName])) {
                    continue;
                }

                $records = $tables[$tableName];

                // Delete scoped data before inserting
                $this->truncateScoped($tableName, $scope);

                // Insert records
                $count = $this->insertRecords($tableName, $records);
                $restored[$tableName] = $count;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return ['restored' => $restored, 'errors' => $errors];
    }

    /**
     * Restore all data from a full backup.
     */
    public function restoreAll(array $backupData): array
    {
        $tables = $backupData['tables'] ?? [];
        $restored = [];

        // Move FK check outside transaction to avoid scope issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            DB::beginTransaction();

            // Delete only tables that we have data for (in reverse order)
            foreach (array_reverse($this->fullRestoreOrder) as $tableName) {
                if (isset($tables[$tableName])) {
                    DB::table($tableName)->delete();
                }
            }

            // Insert in correct dependency order
            foreach ($this->fullRestoreOrder as $tableName) {
                if (!isset($tables[$tableName])) {
                    continue;
                }
                $count = $this->insertRecords($tableName, $tables[$tableName]);
                $restored[$tableName] = $count;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return ['restored' => $restored];
    }

    /**
     * Save backup data to a JSON file in storage.
     */
    public function saveToFile(array $data, string $filename = null): string
    {
        $filename = $filename ?: $this->generateFilename($data['meta']['category'] ?? 'backup');

        Storage::disk('local')->makeDirectory('backups');

        $path = 'backups/' . $filename;
        Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $path;
    }

    /**
     * Log a backup operation.
     */
    public function logBackup(string $category, string $status, string $filePath = null, string $message = null): BackupLog
    {
        return BackupLog::create([
            'category' => $category,
            'type' => 'backup',
            'status' => $status,
            'file_path' => $filePath,
            'file_size' => $filePath && Storage::disk('local')->exists($filePath)
                ? Storage::disk('local')->size($filePath)
                : null,
            'message' => $message,
            'performed_by' => auth()->id(),
        ]);
    }

    /**
     * Log a restore operation.
     */
    public function logRestore(string $category, string $status, string $message = null): BackupLog
    {
        return BackupLog::create([
            'category' => $category,
            'type' => 'restore',
            'status' => $status,
            'message' => $message,
            'performed_by' => auth()->id(),
        ]);
    }

    /**
     * Get backup history logs.
     */
    public function getHistory(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return BackupLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * List available backup files from storage.
     */
    public function listBackupFiles(): array
    {
        $files = Storage::disk('local')->files('backups');
        $result = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $result[] = [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'last_modified' => Carbon::createFromTimestamp(
                        Storage::disk('local')->lastModified($file)
                    )->toDateTimeString(),
                ];
            }
        }

        // Sort by newest first
        usort($result, function ($a, $b) {
            return strcmp($b['last_modified'], $a['last_modified']);
        });

        return $result;
    }

    /**
     * Run a scheduled backup (called from artisan command).
     */
    public function runScheduledBackup(string $frequency = 'daily'): bool
    {
        try {
            $data = $this->exportAll();
            $filename = 'scheduled_' . $frequency . '_' . Carbon::now()->format('Y-m-d_His') . '.json';
            $path = $this->saveToFile($data, $filename);

            $this->logBackup('full', 'success', $path, "Scheduled {$frequency} backup completed");

            // Clean old scheduled backups (keep last 30)
            $this->cleanOldScheduledBackups(30);

            return true;
        } catch (\Exception $e) {
            $this->logBackup('full', 'failed', null, "Scheduled {$frequency} backup failed: " . $e->getMessage());
            return false;
        }
    }

    // ─── PRIVATE HELPERS ─────────────────────────────────────────

    /**
     * Fetch data for a table definition (handles scoping for shared tables).
     */
    private function fetchTableData(string $tableDef): array
    {
        $parsed = $this->parseTableDef($tableDef);
        $tableName = $parsed['table'];
        $scope = $parsed['scope'];

        $query = DB::table($tableName);

        // Handle scoped queries for shared tables
        if ($scope === 'purchase_order') {
            $query->whereNotNull('purchase_order_id');
        } elseif ($scope === 'sales_order') {
            $query->whereNotNull('sales_order_id');
        } elseif ($scope === 'product_return') {
            $query->whereNotNull('product_return_id');
        }

        $records = $query->get()->map(function ($record) {
            return (array) $record;
        })->toArray();

        return [
            'table' => $tableName,
            'records' => $records,
        ];
    }

    /**
     * Parse a table definition string like "product_details:purchase_order".
     */
    private function parseTableDef(string $tableDef): array
    {
        $parts = explode(':', $tableDef);
        return [
            'table' => $parts[0],
            'scope' => $parts[1] ?? null,
        ];
    }

    /**
     * Truncate table data, respecting scope for shared tables.
     */
    private function truncateScoped(string $tableName, ?string $scope): void
    {
        if ($scope === 'purchase_order') {
            DB::table($tableName)->whereNotNull('purchase_order_id')->delete();
        } elseif ($scope === 'sales_order') {
            DB::table($tableName)->whereNotNull('sales_order_id')->delete();
        } elseif ($scope === 'product_return') {
            DB::table($tableName)->whereNotNull('product_return_id')->delete();
        } else {
            DB::table($tableName)->delete();
        }
    }

    /**
     * Insert records into a table in chunks.
     */
    private function insertRecords(string $tableName, array $records): int
    {
        if (empty($records)) {
            return 0;
        }

        $chunks = array_chunk($records, 500);
        $count = 0;

        foreach ($chunks as $chunk) {
            DB::table($tableName)->insert($chunk);
            $count += count($chunk);
        }

        return $count;
    }

    /**
     * Generate a backup filename.
     */
    private function generateFilename(string $category): string
    {
        $timestamp = Carbon::now()->format('Y-m-d_His');
        $safeCategory = preg_replace('/[^a-zA-Z0-9_]/', '_', $category);
        return "backup_{$safeCategory}_{$timestamp}.json";
    }

    /**
     * Clean old scheduled backup files, keeping the N most recent.
     */
    private function cleanOldScheduledBackups(int $keep = 30): void
    {
        $files = Storage::disk('local')->files('backups');
        $scheduledFiles = [];

        foreach ($files as $file) {
            if (strpos(basename($file), 'scheduled_') === 0) {
                $scheduledFiles[] = $file;
            }
        }

        // Sort by modification time descending
        usort($scheduledFiles, function ($a, $b) {
            return Storage::disk('local')->lastModified($b) - Storage::disk('local')->lastModified($a);
        });

        // Delete files beyond the keep limit
        $toDelete = array_slice($scheduledFiles, $keep);
        foreach ($toDelete as $file) {
            Storage::disk('local')->delete($file);
        }
    }
}
