<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Integrations\Streetview\Streetview;

class LocationSearch extends Command {
    protected $signature = 'location:search {limit=999}';
    protected $description = 'Update panorama location from Nominatim';

    public function handle(): void {
        $locations = DB::select("
            SELECT cc.country_code, cc.count, lc.lat, lc.lng
            FROM location_cities_500 lc
            LEFT JOIN (
                SELECT country_code, count(*) as count FROM panorama
                GROUP BY country_code
                ORDER BY count
            ) as cc ON cc.country_code = lc.country_code
            WHERE cc.count IS NULL OR cc.count < 200
            ORDER BY random()
            LIMIT ?
        ", [$this->argument('limit')]);

        $hit = [];
        $miss = [];
        foreach ($locations as $location) {
            $res = Streetview::findNearbyPanorama($location->lat, $location->lng, attempts: 5, precision: 60);

            $this->info(json_encode($location));
            $this->info($res);
        }
    }
}