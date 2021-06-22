<?php

namespace App\Providers;

use App\Commands\Test;
use App\Commands\Translate;
use App\Commands\UpdateRanks;
use App\Commands\GenerateModels;
use App\Commands\LocationSearch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel;
use App\Commands\UpdateLocationInformation;

class ConsoleKernel extends Kernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateModels::class,
        Translate::class,
        UpdateLocationInformation::class,
        UpdateRanks::class,
        LocationSearch::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule):void {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands():void {
    }
}
