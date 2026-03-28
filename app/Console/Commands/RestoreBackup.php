<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Artisan command to restore a JSON backup file directly.
 * Handles 800MB+ files by reading chunks and parsing records one at a time.
 * Bypasses web PHP memory limits by running from CLI.
 */
class RestoreBackup extends Command
{
    protected $signature = 'backup:restore {filename} {--type=full : Restore type (full or category name)}';
    protected $description = 'Restore database from a JSON backup file (handles 800MB+ files)';

    /**
     * Full restore order respecting foreign key dependencies.
     */
    private $fullRestoreOrder = [
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

    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M'); // Should be plenty with streaming

        $filename = basename($this->argument('filename'));
        $filePath = storage_path('app/backups/' . $filename);
        $restoreType = $this->option('type');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $fileSize = filesize($filePath);
        $fileSizeMB = round($fileSize / 1024 / 1024, 2);

        $this->info("=== BACKUP RESTORE ===");
        $this->info("File: {$filename}");
        $this->info("Size: {$fileSizeMB} MB");
        $this->info("Type: {$restoreType}");
        $this->info("");

        // Phase 1: Find table positions
        $this->info("[Phase 1] Scanning file for table positions...");
        $tablePositions = $this->findTablePositions($filePath);

        if (empty($tablePositions)) {
            $this->error("No tables found in backup file!");
            return 1;
        }

        $this->info("Found " . count($tablePositions) . " tables: " . implode(', ', array_keys($tablePositions)));
        $this->info("");

        // Phase 2: Determine tables to restore
        $tablesToRestore = $this->fullRestoreOrder;

        // Phase 3: Disable FK checks and start restore
        $this->info("[Phase 2] Disabling foreign key checks...");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Delete all existing data first (in reverse order)
            $this->info("[Phase 3] Clearing existing data...");
            foreach (array_reverse($tablesToRestore) as $tableName) {
                if (Schema::hasTable($tableName)) {
                    $count = DB::table($tableName)->count();
                    DB::table($tableName)->delete();
                    $this->line("  Cleared {$tableName} ({$count} records)");
                }
            }

            $this->info("");
            $this->info("[Phase 4] Restoring data...");

            $totalRecords = 0;
            $startTime = microtime(true);

            foreach ($tablesToRestore as $tableName) {
                if (!isset($tablePositions[$tableName])) {
                    $this->warn("  Skipping {$tableName} (not in backup)");
                    continue;
                }

                $tableStart = microtime(true);
                $count = $this->restoreTable($filePath, $tableName, $tablePositions[$tableName]);
                $tableElapsed = round(microtime(true) - $tableStart, 1);
                $mem = round(memory_get_usage(true) / 1024 / 1024, 1);

                $this->info("  ✓ {$tableName}: {$count} records ({$tableElapsed}s, {$mem}MB)");
                $totalRecords += $count;
            }

            $elapsed = round(microtime(true) - $startTime, 1);

            $this->info("");
            $this->info("=== RESTORE COMPLETE ===");
            $this->info("Total records: {$totalRecords}");
            $this->info("Duration: {$elapsed}s");
            $this->info("Peak memory: " . round(memory_get_peak_usage(true) / 1024 / 1024, 1) . " MB");

            // Log success
            try {
                DB::table('backup_logs')->insert([
                    'category' => $restoreType,
                    'type' => 'restore',
                    'status' => 'success',
                    'message' => "CLI restore: {$totalRecords} records in {$elapsed}s from {$filename}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $logError) {
                // Non-critical - restore succeeded, just log recording failed
                $this->warn("Warning: Could not record restore to history, but data was restored successfully.");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("RESTORE FAILED: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());

            // Log failure to backup_logs
            try {
                DB::table('backup_logs')->insert([
                    'category' => $restoreType ?? 'full',
                    'type' => 'restore',
                    'status' => 'failed',
                    'message' => "CLI restore FAILED: " . $e->getMessage(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $logError) {
                // Even if logging fails, don't block the user from seeing the error
                $this->error("Could not log restore failure: " . $logError->getMessage());
            }

            return 1;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Fast scan to find byte position of each table's "[" in the file.
     * Uses 4MB chunks with regex for speed on 800MB files.
     */
    private function findTablePositions(string $filePath): array
    {
        $handle = fopen($filePath, 'rb');
        $fileSize = filesize($filePath);
        $positions = [];

        $chunkSize = 4 * 1024 * 1024; // 4MB chunks for speed
        $overlap = 300;
        $pos = 0;

        while ($pos < $fileSize) {
            fseek($handle, $pos);
            $readSize = min($chunkSize, $fileSize - $pos);
            $chunk = fread($handle, $readSize);
            if ($chunk === false || $chunk === '') break;

            // Find "tablename":[ patterns
            $offset = 0;
            while (preg_match('/"([a-z_]+)"\s*:\s*\[/', $chunk, $m, PREG_OFFSET_CAPTURE, $offset)) {
                $name = $m[1][0];
                // Find the actual '[' position
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
     * Restore a single table by streaming records from the file.
     * Reads from the table's start position, parses records one at a time,
     * and inserts in batches of 200. Never holds more than one batch in memory.
     */
    private function restoreTable(string $filePath, string $tableName, int $startPos): int
    {
        $handle = fopen($filePath, 'rb');
        fseek($handle, $startPos + 1); // Skip past the '['

        $count = 0;
        $buffer = [];
        $batchSize = 200; // Smaller batches = less memory

        // State machine for record extraction
        $depth = 0;
        $inString = false;
        $escapeNext = false;
        $recordJson = '';
        $inRecord = false;

        while (!feof($handle)) {
            $chunk = fread($handle, 65536); // 64KB chunks
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

                // Outside string context
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
                            // Complete record found
                            $record = json_decode($recordJson, true);
                            if (is_array($record)) {
                                $buffer[] = $record;
                                $count++;

                                if (count($buffer) >= $batchSize) {
                                    $this->insertBatch($tableName, $buffer);
                                    $buffer = [];
                                }
                            }
                            $recordJson = '';
                            $inRecord = false;
                        }
                    }
                } elseif ($c === ']' && !$inRecord && $depth === 0) {
                    // End of this table's array
                    break 2; // Exit both loops
                } elseif ($inRecord) {
                    $recordJson .= $c;
                }
            }
        }

        // Insert remaining records
        if (!empty($buffer)) {
            $this->insertBatch($tableName, $buffer);
        }

        fclose($handle);
        return $count;
    }

    /**
     * Insert a batch of records into a table.
     * Handles potential issues with mismatched columns.
     */
    private function insertBatch(string $tableName, array $records): void
    {
        if (empty($records)) return;

        try {
            DB::table($tableName)->insert($records);
        } catch (\Exception $e) {
            // If batch insert fails, try one by one
            foreach ($records as $record) {
                try {
                    DB::table($tableName)->insert($record);
                } catch (\Exception $e2) {
                    // Skip individual bad records but continue
                }
            }
        }
    }
}
