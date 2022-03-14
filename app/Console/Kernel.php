<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// use App\Http\Controllers\UrlScrapper;
use Illuminate\Support\Stringable;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call('App\Http\Controllers\UrlScrapper@callfordata')->everyMinute()
        -> onSuccess(function(Stringable $output){
            echo $output;
        })
        -> onFailure(function(Stringable $output){
            echo "failed task";
        });
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
