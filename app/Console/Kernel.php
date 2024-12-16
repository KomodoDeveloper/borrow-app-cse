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
        Commands\CheckEndBorrow::class,
        Commands\CheckAvailability::class,
        Commands\CheckStartBorrow::class,
        Commands\CheckBorrowReturn::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('checkendborrow:day')->dailyAt('06:00');
        $schedule->command('checkstartborrow:daily')->dailyAt('06:30');
        $schedule->command('checkavailability:daily')->dailyAt('05:00');
        //$schedule->command('checkborrowreturn:daily')->dailyAt('05:30');
        //At 05:00:00am, every 2 days starting on the 1st, every month : 0 7 */2 * *
        $schedule->command('checkborrowreturn:daily')->cron('0 7 */2 * *');

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
