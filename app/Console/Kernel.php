<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
        protected $commands = [

        Commands\AbandonedCart::class,
            // Commands\DeleteStore::class,
            // Commands\PermanentDeletion::class,
            Commands\ArchiveIdleOrders::class,
            Commands\DeleteTokenFile::class

    ];
    protected function schedule(Schedule $schedule)
    {
          $schedule->command('cart:abandoned') 
                ->everyMinute();
                //  $schedule->command('store:delete') 
                // ->everyMinute();
                // $schedule->command('deletion:permanent') 
                // ->everyMinute();
                $schedule->command('orders:archive') 
                ->everyMinute();
                $schedule->command('token:deletefile') 
                ->everyMinute();
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
