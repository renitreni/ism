<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class ScheduledBackup extends Command
{
    protected $signature = 'backup:run {frequency=daily : The backup frequency (daily, weekly, monthly)}';

    protected $description = 'Run a scheduled database backup';

    public function handle(BackupService $backupService)
    {
        $frequency = $this->argument('frequency');

        if (!in_array($frequency, ['daily', 'weekly', 'monthly'])) {
            $this->error("Invalid frequency: {$frequency}. Use daily, weekly, or monthly.");
            return 1;
        }

        $this->info("Starting {$frequency} backup...");

        $success = $backupService->runScheduledBackup($frequency);

        if ($success) {
            $this->info("Scheduled {$frequency} backup completed successfully.");
            return 0;
        }

        $this->error("Scheduled {$frequency} backup failed. Check backup_logs for details.");
        return 1;
    }
}
