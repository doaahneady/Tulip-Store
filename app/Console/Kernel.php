<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('compliance:enforce-retention')->dailyAt('02:00');
        $schedule->command('trader:payouts:generate')->monthlyOn(1, '03:00');
        $schedule->command('coupons:generate-birthday')->dailyAt('00:01'); // Run at 12:01 AM every day
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
