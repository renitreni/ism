<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Daily backup at 2:00 AM
        $schedule->command('backup:run daily')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/backup.log'));

        // Weekly backup on Sunday at 3:00 AM
        $schedule->command('backup:run weekly')
                 ->weeklyOn(0, '03:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/backup.log'));

        // Monthly backup on the 1st at 4:00 AM
        $schedule->command('backup:run monthly')
                 ->monthlyOn(1, '04:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/backup.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
