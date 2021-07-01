<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocationFix extends Command {
    protected $signature = 'location:fix';
    protected $description = 'Fix location from Nominatim';

    public function handle(): void {
        $this->fixSvalbard();
        $this->fixAAland();
        $this->fixAntarctica();
        $this->fixRemaining();
    }

    private function fixSvalbard(): void {
        $tmp = DB::update("
            UPDATE panorama SET extended_country_code = 'SJ' 
            WHERE region_name = 'Svalbard' AND extended_country_code != 'SJ'
        ");
        $tmp += DB::update("
            UPDATE round_user SET extended_country_code = 'SJ' 
            WHERE region_name = 'Svalbard' AND extended_country_code != 'SJ'
        ");
        if ($tmp > 0) {
            $this->info("Fixed $tmp Svalbard.");
        }
    }

    private function fixAAland(): void {
        $tmp = DB::update("
            UPDATE panorama SET extended_country_code = 'AX' 
            WHERE (county_name = 'Åland' OR county_name = 'Landskapet Åland') AND extended_country_code != 'AX'
        ");
        $tmp += DB::update("
            UPDATE round_user SET extended_country_code = 'AX' 
            WHERE (county_name = 'Åland' OR county_name = 'Landskapet Åland') AND extended_country_code != 'AX'
        ");
        if ($tmp > 0) {
            $this->info("Fixed $tmp Åland.");
        }
    }

    private function fixAntarctica(): void {
        $tmp = DB::update("
            UPDATE panorama SET country_code = 'AQ', extended_country_code = 'AQ', county_name = 'antarctica'
            WHERE ST_Y(panorama_location::geometry) <= -60 AND extended_country_code IS NULL
        ");
        $tmp += DB::update("
            UPDATE round_user SET country_code = 'AQ', extended_country_code = 'AQ', county_name = 'antarctica'
            WHERE ST_Y(location::geometry) <= -60 AND extended_country_code IS NULL
        ");
        if ($tmp > 0) {
            $this->info("Fixed $tmp Antarctica.");
        }
    }

    private function fixRemaining(): void {
        $tmp = DB::update("UPDATE panorama SET extended_country_code = country_code WHERE extended_country_code IS NULL");
        $tmp +=DB::update("UPDATE round_user SET extended_country_code = country_code WHERE extended_country_code IS NULL");
        if ($tmp > 0) {
            $this->info("Fixed $tmp remaining.");
        }
    }
}