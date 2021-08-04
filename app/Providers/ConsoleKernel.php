<?php

namespace App\Providers;

use App\Commands\Translate;
use App\Commands\UpdateRanks;
use App\Commands\GenerateModels;
use App\Commands\LocationSearch;
use App\Commands\ImportPanoramas;
use Illuminate\Support\Facades\DB;
use App\Commands\UpdateAchievements;
use Illuminate\Support\Facades\File;
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
    protected function schedule(Schedule $schedule): void {
        $schedule->command('location:update')->everyTwoMinutes();
        $schedule->command('zz:achievements')->dailyAt('0:35');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void {
        Artisan::command('zz:test', function () {
            $pano2  = DB::select("
                select p.panorama_id,  p.jpg_name FROM panorama p WHERE 
                                               NOT EXISTS(SELECT * FROM round r WHERE r.panorama_id = p.panorama_id) AND 
                                               p.captured_date < '2011-01-01' AND p.added_by_user_id IS NULL
            ");
               $this->info(count($pano2));

            foreach ($pano2 as $p) {
                $res = File::delete(storage_path('app/public/sv-jpg/') . $p->jpg_name . '.jpg');
                $this->info($res);
                if ($res)  {
                    DB::delete("DELETE FROM panorama WHERE panorama_id = ?", [$p->panorama_id]);
                }
            }
        });
    }
}
