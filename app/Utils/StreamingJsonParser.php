<?php

namespace App\Utils;

/**
 * Streaming JSON parser for large backup files (800MB+).
 * Processes one table at a time to avoid memory exhaustion.
 * Handles the ISM backup format: {"meta": {...}, "tables": {"table_name": [records...]}}
 *
 * Strategy: Instead of pre-scanning the entire file byte-by-byte (too slow for 800MB),
 * we use fast strpos/regex searches to find table name positions, then stream-parse
 * only the records section of each table on demand.
 */
class StreamingJsonParser
{
    private $filePath;
    private $fileSize;
    private $tablePositions = []; // table_name => byte position of the '[' that starts its records

    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("File not found: {$filePath}");
        }

        $this->filePath = $filePath;
        $this->fileSize = filesize($filePath);
        $this->scanTablePositions();
    }

    /**
     * Fast scan: Find position of each "table_name":[ in the file.
     * Uses large chunk reads with regex for speed instead of char-by-char parsing.
     * Only stores the byte position of '[' for each table.
     */
    private function scanTablePositions(): void
    {
        $handle = fopen($this->filePath, 'rb');
        if (!$handle) {
            throw new \RuntimeException("Cannot open file: {$this->filePath}");
        }

        try {
            // Phase 1: Find "tables":{ using fast search
            $tablesPos = $this->findStringInFile($handle, '"tables":{');
            if ($tablesPos === false) {
                return;
            }

            // Phase 2: From tables position, scan forward for "tablename":[ patterns
            // Use large chunks (1MB) for speed
            $chunkSize = 1024 * 1024; // 1MB chunks
            $pos = $tablesPos;
            $overlap = 200; // Overlap to catch patterns split across chunks

            fseek($handle, $pos);

            while ($pos < $this->fileSize) {
                $readSize = min($chunkSize, $this->fileSize - $pos);
                $chunk = fread($handle, $readSize);
                if ($chunk === false || $chunk === '') {
                    break;
                }

                // Use regex to find "word":[ patterns in this chunk
                // This is MUCH faster than char-by-char parsing
                $offset = 0;
                while (preg_match('/"([a-z_]+)"\s*:\s*\[/', $chunk, $matches, PREG_OFFSET_CAPTURE, $offset)) {
                    $tableName = $matches[1][0];
                    $bracketOffset = strpos($chunk, '[', $matches[0][1]);
                    $absolutePos = $pos + $bracketOffset;

                    // Only store if we haven't seen this table yet
                    if (!isset($this->tablePositions[$tableName])) {
                        $this->tablePositions[$tableName] = $absolutePos;
                    }

                    $offset = $matches[0][1] + strlen($matches[0][0]);
                }

                $pos += strlen($chunk);

                // Seek back slightly for overlap (in case a pattern spans chunk boundary)
                if ($pos < $this->fileSize) {
                    $pos -= $overlap;
                    fseek($handle, $pos);
                }
            }
        } finally {
            fclose($handle);
        }
    }

    /**
     * Find a string in the file efficiently using large chunk reads.
     * Returns the byte position right AFTER the needle, or false.
     */
    private function findStringInFile($handle, string $needle)
    {
        rewind($handle);
        $pos = 0;
        $needleLen = strlen($needle);
        $chunkSize = 1024 * 1024; // 1MB
        $prevTail = '';

        while ($pos < $this->fileSize) {
            $readSize = min($chunkSize, $this->fileSize - $pos);
            $chunk = fread($handle, $readSize);
            if ($chunk === false || $chunk === '') {
                break;
            }

            $searchBuffer = $prevTail . $chunk;
            $found = strpos($searchBuffer, $needle);

            if ($found !== false) {
                return $pos - strlen($prevTail) + $found + $needleLen;
            }

            $pos += strlen($chunk);
            $prevTail = substr($chunk, -$needleLen);
        }

        return false;
    }

    /**
     * Check if a table exists in the backup.
     */
    public function hasTable(string $tableName): bool
    {
        return isset($this->tablePositions[$tableName]);
    }

    /**
     * Get all table names in the backup.
     */
    public function getTableNames(): array
    {
        return array_keys($this->tablePositions);
    }

    /**
     * Extract and decode all records for a specific table into an array.
     * WARNING: For large tables this can use a LOT of memory.
     * Prefer iterateTableRecords() for restore operations.
     */
    public function getTableRecords(string $tableName): array
    {
        $records = [];
        foreach ($this->iterateTableRecords($tableName) as $record) {
            $records[] = $record;
        }
        return $records;
    }

    /**
     * Iterate records for a specific table using a Generator.
     * Yields one record at a time so memory usage stays constant
     * regardless of table size. Reads 64KB chunks from the file.
     */
    public function iterateTableRecords(string $tableName): \Generator
    {
        if (!$this->hasTable($tableName)) {
            return;
        }

        $startPos = $this->tablePositions[$tableName] + 1; // Skip past the '['

        $handle = fopen($this->filePath, 'rb');
        if (!$handle) {
            throw new \RuntimeException("Cannot open file: {$this->filePath}");
        }

        try {
            fseek($handle, $startPos);

            $depth = 0;
            $inString = false;
            $escapeNext = false;
            $recordJson = '';
            $inRecord = false;
            $bytesRead = 0;
            $maxBytes = $this->fileSize - $startPos;

            while ($bytesRead < $maxBytes) {
                $readSize = min(65536, $maxBytes - $bytesRead);
                $chunk = fread($handle, $readSize);
                if ($chunk === false || $chunk === '') {
                    break;
                }
                $chunkLen = strlen($chunk);
                $bytesRead += $chunkLen;

                for ($i = 0; $i < $chunkLen; $i++) {
                    $char = $chunk[$i];

                    if ($escapeNext) {
                        if ($inRecord) {
                            $recordJson .= $char;
                        }
                        $escapeNext = false;
                        continue;
                    }

                    if ($char === '\\' && $inString) {
                        if ($inRecord) {
                            $recordJson .= $char;
                        }
                        $escapeNext = true;
                        continue;
                    }

                    if ($char === '"') {
                        $inString = !$inString;
                        if ($inRecord) {
                            $recordJson .= $char;
                        }
                        continue;
                    }

                    if ($inString) {
                        if ($inRecord) {
                            $recordJson .= $char;
                        }
                        continue;
                    }

                    // Outside strings
                    if ($char === '{') {
                        $depth++;
                        if (!$inRecord) {
                            $inRecord = true;
                            $recordJson = '{';
                        } else {
                            $recordJson .= $char;
                        }
                    } elseif ($char === '}') {
                        $depth--;
                        if ($inRecord) {
                            $recordJson .= $char;
                            if ($depth === 0) {
                                $decoded = json_decode($recordJson, true);
                                if ($decoded !== null && is_array($decoded)) {
                                    yield $decoded;
                                }
                                $recordJson = '';
                                $inRecord = false;
                            }
                        }
                    } elseif ($char === ']' && !$inRecord) {
                        // End of this table's array
                        return;
                    } elseif ($inRecord) {
                        $recordJson .= $char;
                    }
                }
            }
        } finally {
            fclose($handle);
        }
    }
}

