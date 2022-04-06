<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// use App\Http\Controllers\TimerController;
use Illuminate\Support\Stringable;
use App\Http\Controllers\CommunicateController;
use App\Http\Controllers\UrlScrapper;


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
        $schedule->call(function(){
            echo (new UrlScrapper)->callfordata();
        })->everyTenMinutes();

        $schedule->call(function(){
            echo (new CommunicateController)->sendgooddeals();
        })->everyThreeMinutes();
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
