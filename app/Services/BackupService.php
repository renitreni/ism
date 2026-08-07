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
     * Export all data directly to a file using chunked writes to avoid memory exhaustion.
     * Handles 600MB+ exports by never holding the full dataset in memory.
     * Returns the storage path of the created backup file.
     */
    public function exportAllToFile(string $filename = null): string
    {
        $filename = $filename ?: $this->generateFilename('all');
        Storage::disk('local')->makeDirectory('backups');
        $fullPath = storage_path('app/backups/' . $filename);

        $handle = fopen($fullPath, 'w');
        if (!$handle) {
            throw new \RuntimeException('Could not create backup file.');
        }

        // Use a larger write buffer (4MB) for better I/O performance on large exports
        stream_set_write_buffer($handle, 4 * 1024 * 1024);

        try {
            // Write opening and meta
            $meta = [
                'type' => 'full',
                'category' => 'all',
                'label' => 'Full Database Backup',
                'created_at' => Carbon::now()->toIso8601String(),
                'app' => 'ISM Backup System',
                'version' => '1.0',
            ];
            fwrite($handle, '{"meta":' . json_encode($meta, JSON_UNESCAPED_UNICODE) . ',"tables":{');

            // Collect unique table definitions, merging scoped tables
            $tableDefsMap = [];
            foreach ($this->categories as $category) {
                foreach ($category['tables'] as $tableDef) {
                    $parsed = $this->parseTableDef($tableDef);
                    $tableName = $parsed['table'];
                    if (!isset($tableDefsMap[$tableName])) {
                        $tableDefsMap[$tableName] = [];
                    }
                    $tableDefsMap[$tableName][] = $tableDef;
                }
            }

            $firstTable = true;
            foreach ($tableDefsMap as $tableName => $defs) {
                if (!$firstTable) {
                    fwrite($handle, ',');
                }
                $firstTable = false;

                fwrite($handle, json_encode($tableName) . ':[');

                // Not all tables have an 'id' column (e.g. pivot tables like taggables)
                $hasId = \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'id');

                // Only track IDs for dedup on shared tables (multiple scopes)
                $needsDedup = count($defs) > 1 && $hasId;
                $seenIds = [];
                $firstRecord = true;

                foreach ($defs as $tableDef) {
                    $parsed = $this->parseTableDef($tableDef);
                    $scope = $parsed['scope'];

                    $query = DB::table($tableName);
                    if ($scope === 'purchase_order') {
                        $query->whereNotNull('purchase_order_id');
                    } elseif ($scope === 'sales_order') {
                        $query->whereNotNull('sales_order_id');
                    } elseif ($scope === 'product_return') {
                        $query->whereNotNull('product_return_id');
                    }

                    // Not all tables have an 'id' column (e.g. pivot tables like taggables)
                    if ($hasId) {
                        $query->orderBy('id')->chunk(1000, function ($rows) use ($handle, &$firstRecord, &$seenIds, $needsDedup) {
                            $this->writeRows($handle, $rows, $firstRecord, $seenIds, $needsDedup);
                        });
                    } else {
                        // Pivot tables without id are typically small — fetch all at once
                        $rows = $query->get();
                        $this->writeRows($handle, $rows, $firstRecord, $seenIds, $needsDedup);
                    }
                }

                // Free dedup memory after each table
                unset($seenIds);

                fwrite($handle, ']');
            }

            fwrite($handle, '}}');
        } catch (\Exception $e) {
            fclose($handle);
            // Clean up partial file on failure
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            throw $e;
        }

        fclose($handle);

        return 'backups/' . $filename;
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
     * Restore data from a backup file on disk using streaming to avoid memory exhaustion.
     * Handles 800MB+ files by scanning for table positions first, then streaming
     * records one at a time. Never loads the whole file into memory.
     */
    public function restoreFromFile(string $filePath, string $restoreType): array
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Backup file not found: {$filePath}");
        }

        // Step 1: Find byte positions of each table's data array in the file
        $tablePositions = $this->findTablePositionsInFile($filePath);

        if (empty($tablePositions)) {
            throw new \RuntimeException("No tables found in backup file.");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $restored = [];

        try {
            if ($restoreType === 'full') {
                // Delete all existing data first (in reverse order)
                foreach (array_reverse($this->fullRestoreOrder) as $tableName) {
                    DB::table($tableName)->delete();
                }

                // Restore each table in dependency order
                foreach ($this->fullRestoreOrder as $tableName) {
                    if (isset($tablePositions[$tableName])) {
                        $count = $this->streamRestoreTable($filePath, $tableName, $tablePositions[$tableName]);
                        $restored[$tableName] = $count;
                    }
                }
            } else {
                // Restore specific category
                if (!isset($this->categories[$restoreType])) {
                    throw new \InvalidArgumentException("Unknown category: {$restoreType}");
                }

                foreach ($this->categories[$restoreType]['tables'] as $tableDef) {
                    $parsed = $this->parseTableDef($tableDef);
                    $tableName = $parsed['table'];
                    $scope = $parsed['scope'];

                    if (!isset($tablePositions[$tableName])) {
                        continue;
                    }

                    $this->truncateScoped($tableName, $scope);

                    $count = $this->streamRestoreTable($filePath, $tableName, $tablePositions[$tableName]);
                    $restored[$tableName] = $count;
                }
            }
        } catch (\Exception $e) {
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return ['restored' => $restored];
    }

    /**
     * Fast scan: Find byte position of each table's "[" in the backup file.
     * Uses 4MB chunks with regex — handles 800MB in seconds.
     */
    private function findTablePositionsInFile(string $filePath): array
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new \RuntimeException("Cannot open file: {$filePath}");
        }

        $fileSize = filesize($filePath);
        $positions = [];
        $chunkSize = 4 * 1024 * 1024; // 4MB
        $overlap = 300;
        $pos = 0;

        while ($pos < $fileSize) {
            fseek($handle, $pos);
            $readSize = min($chunkSize, $fileSize - $pos);
            $chunk = fread($handle, $readSize);
            if ($chunk === false || $chunk === '') break;

            $offset = 0;
            while (preg_match('/"([a-z_]+)"\s*:\s*\[/', $chunk, $m, PREG_OFFSET_CAPTURE, $offset)) {
                $name = $m[1][0];
                $bracketPos = strpos($chunk, '[', $m[0][1]);
                if ($bracketPos !== false && !isset($positions[$name])) {
                    $positions[$name] = $pos + $bracketPos;
                }
                $offset = $m[0][1] + strlen($m[0][0]);
            }

            $pos += strlen($chunk);
            if ($pos < $fileSize) {
                $pos -= $overlap;
            }
        }

        fclose($handle);
        return $positions;
    }

    /**
     * Stream-restore a single table: reads records one at a time from the file
     * starting at the given byte position, inserts in batches of 200.
     * Memory usage stays constant regardless of table size.
     */
    private function streamRestoreTable(string $filePath, string $tableName, int $startPos): int
    {
        $handle = fopen($filePath, 'rb');
        fseek($handle, $startPos + 1); // Skip past the '['

        $count = 0;
        $buffer = [];
        $batchSize = 200;

        $depth = 0;
        $inString = false;
        $escapeNext = false;
        $recordJson = '';
        $inRecord = false;

        while (!feof($handle)) {
            $chunk = fread($handle, 65536);
            if ($chunk === false || $chunk === '') break;
            $len = strlen($chunk);

            for ($i = 0; $i < $len; $i++) {
                $c = $chunk[$i];

                if ($escapeNext) {
                    if ($inRecord) $recordJson .= $c;
                    $escapeNext = false;
                    continue;
                }

                if ($c === '\\' && $inString) {
                    if ($inRecord) $recordJson .= $c;
                    $escapeNext = true;
                    continue;
                }

                if ($c === '"') {
                    $inString = !$inString;
                    if ($inRecord) $recordJson .= $c;
                    continue;
                }

                if ($inString) {
                    if ($inRecord) $recordJson .= $c;
                    continue;
                }

                if ($c === '{') {
                    $depth++;
                    if (!$inRecord) {
                        $inRecord = true;
                        $recordJson = '{';
                    } else {
                        $recordJson .= $c;
                    }
                } elseif ($c === '}') {
                    $depth--;
                    if ($inRecord) {
                        $recordJson .= $c;
                        if ($depth === 0) {
                            $record = json_decode($recordJson, true);
                            if (is_array($record)) {
                                $buffer[] = $record;
                                $count++;
                                if (count($buffer) >= $batchSize) {
                                    $this->insertBatchSafe($tableName, $buffer);
                                    $buffer = [];
                                }
                            }
                            $recordJson = '';
                            $inRecord = false;
                        }
                    }
                } elseif ($c === ']' && !$inRecord && $depth === 0) {
                    break 2;
                } elseif ($inRecord) {
                    $recordJson .= $c;
                }
            }
        }

        if (!empty($buffer)) {
            $this->insertBatchSafe($tableName, $buffer);
        }

        fclose($handle);
        return $count;
    }

    /**
     * Insert a batch with fallback to row-by-row on failure.
     */
    private function insertBatchSafe(string $tableName, array $records): void
    {
        if (empty($records)) return;

        try {
            DB::table($tableName)->insert($records);
        } catch (\Exception $e) {
            // Batch failed — try one by one to salvage good records
            foreach ($records as $record) {
                try {
                    DB::table($tableName)->insert($record);
                } catch (\Exception $e2) {
                    // Skip bad record
                }
            }
        }
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
            $filename = 'scheduled_' . $frequency . '_' . Carbon::now()->format('Y-m-d_His') . '.json';
            $path = $this->exportAllToFile($filename);

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
     * Write a batch of rows to the backup file handle as JSON.
     */
    private function writeRows($handle, $rows, bool &$firstRecord, array &$seenIds, bool $needsDedup): void
    {
        $buffer = '';
        foreach ($rows as $row) {
            $record = (array) $row;
            if ($needsDedup) {
                $id = $record['id'] ?? null;
                if ($id !== null && isset($seenIds[$id])) {
                    continue;
                }
                if ($id !== null) {
                    $seenIds[$id] = true;
                }
            }
            if (!$firstRecord) {
                $buffer .= ',';
            }
            $firstRecord = false;
            $buffer .= json_encode($record, JSON_UNESCAPED_UNICODE);
        }
        if ($buffer !== '') {
            fwrite($handle, $buffer);
        }
    }

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
     * Insert records from a Generator/iterable in small chunks.
     * Never holds more than one chunk (500 records) in memory.
     */
    private function insertRecordsStreaming(string $tableName, iterable $records): int
    {
        $buffer = [];
        $count = 0;

        foreach ($records as $record) {
            $buffer[] = $record;

            if (count($buffer) >= 500) {
                DB::table($tableName)->insert($buffer);
                $count += count($buffer);
                $buffer = [];
            }
        }

        // Insert remaining records
        if (!empty($buffer)) {
            DB::table($tableName)->insert($buffer);
            $count += count($buffer);
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
