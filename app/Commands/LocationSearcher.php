<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocationSearcher extends Command {
    protected $signature = 'location:search {limit=999}';
    protected $description = 'Update panorama location from Nominatim';

    public function handle(): void {
        $locations = DB::select("
            SELECT cc.*, lc.*
            FROM location_cities_500 lc
            LEFT JOIN (
                SELECT country_code, count(*) as count FROM panorama
                GROUP BY country_code
                ORDER BY count
            ) as cc ON cc.country_code = lc.country_code
            WHERE cc.count IS NULL OR cc.count < 200
            LIMIT ?
        ", [$this->argument('limit')]);

        foreach ($locations as $location) {
            $this->info($location);
        }
    }
}