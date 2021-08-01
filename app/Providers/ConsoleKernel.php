<?php

namespace App\Providers;

use App\Commands\Test;
use App\Commands\Translate;
use App\Commands\UpdateRanks;
use App\Commands\LocationFix;
use App\Commands\GenerateModels;
use App\Commands\LocationSearch;
use App\Commands\ImportPanoramas;
use Illuminate\Support\Facades\DB;
use App\Commands\UpdateAchievements;
use Illuminate\Support\Facades\Artisan;
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
        ImportPanoramas::class,
        LocationSearch::class,
        Translate::class,
        UpdateAchievements::class,
        UpdateLocationInformation::class,
        UpdateRanks::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule):void {
        $schedule->command('location:update')->hourly();
        $schedule->command('zz:achievements')->dailyAt('0:35');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands():void {

    }
}
