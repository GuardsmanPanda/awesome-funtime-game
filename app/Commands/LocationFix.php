<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocationFix extends Command {
    protected $signature = 'location:fix';
    protected $description = 'Fix location from Nominatim';

    public function handle(): void {
        $this->fixAntarctica();
        $this->fixBoth("extended_country_code = country_code WHERE extended_country_code IS NULL", 'remaining');
        $this-> fixCountries();
    }

    private function fixCountries():void {
        $this->fixBoth("extended_country_code = 'PM' WHERE region_name = 'Saint-Pierre-et-Miquelon' AND extended_country_code = 'FR'", 'Saint-Pierre-et-Miquelon');
        $this->fixBoth("extended_country_code = 'MF' WHERE state_name = 'Saint-Martin (France)' AND extended_country_code = 'FR'", 'Saint-Martin (France)');
        $this->fixBoth("extended_country_code = 'PF' WHERE state_name = 'Polynésie Française' AND extended_country_code = 'FR'", 'Polynésie Française');
        $this->fixBoth("extended_country_code = 'BL' WHERE region_name = 'Saint-Barthélemy' AND extended_country_code = 'FR'", 'Saint-Barthélemy');
        $this->fixBoth("extended_country_code = 'NC' WHERE region_name = 'Nouvelle-Calédonie' AND extended_country_code = 'FR'", 'New Caledonia');
        $this->fixBoth("extended_country_code = 'WF' WHERE state_name = 'Wallis-et-Futuna' AND extended_country_code = 'FR'", 'Wallis-et-Futuna');
        $this->fixBoth("extended_country_code = 'GP' WHERE state_name = 'Guadeloupe' AND extended_country_code = 'FR'", 'Guadeloupe');
        $this->fixBoth("extended_country_code = 'MQ' WHERE state_name = 'Martinique' AND extended_country_code = 'FR'", 'Martinique');
        $this->fixBoth("extended_country_code = 'GF' WHERE state_name = 'Guyane' AND extended_country_code = 'FR'", 'French Guyana');
        $this->fixBoth("extended_country_code = 'RE' WHERE state_name = 'La Réunion' AND extended_country_code = 'FR'", 'Reunion');
        $this->fixBoth("extended_country_code = 'YT' WHERE state_name = 'Mayotte' AND extended_country_code = 'FR'", 'Mayotte');

        $this->fixBoth("extended_country_code = 'MP' WHERE state_name = 'Northern Mariana Islands' AND extended_country_code = 'US'", 'Northern Mariana Islands	');
        $this->fixBoth("extended_country_code = 'AS' WHERE state_name = 'American Samoa' AND extended_country_code = 'US'", 'American Samoa');
        $this->fixBoth("extended_country_code = 'PR' WHERE state_name = 'Puerto Rico' AND extended_country_code = 'US'", 'Puerto Rico');
        $this->fixBoth("extended_country_code = 'GU' WHERE state_name = 'Guam' AND extended_country_code = 'US'", 'Guam');

        $this->fixBoth("extended_country_code = 'CX' WHERE city_name = 'Shire of Christmas Island' AND extended_country_code = 'AU'", 'Christmas Island');
        $this->fixBoth("extended_country_code = 'CC' WHERE city_name = 'Shire of Cocos Islands' AND extended_country_code = 'AU'", 'Cocos Island');

        $this->fixBoth("extended_country_code = 'HK' WHERE state_name = '香港 Hong Kong' AND extended_country_code = 'CN'", 'Hong Kong');
        $this->fixBoth("extended_country_code = 'MO' WHERE state_name = '澳門 Macau' AND extended_country_code = 'CN'", 'Macau');

        $this->fixBoth("extended_country_code = 'AX' WHERE (county_name = 'Åland' OR county_name = 'Landskapet Åland') AND extended_country_code = 'FI'", 'Åland');
        $this->fixBoth("extended_country_code = 'SJ' WHERE region_name = 'Svalbard' AND extended_country_code = 'NO'", 'Svalbard');
        $this->fixBoth("extended_country_code = 'CW' WHERE state_name = 'Curaçao' AND extended_country_code = 'NL'", 'Curaçao');
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


    private function fixBoth(string $sql, string $location): void {
        $tmp = 0;
        foreach (['panorama', 'round_user'] as $table) {
            $tmp += DB::update("UPDATE ".$table." SET " . $sql);
        }
        if ($tmp > 0) {
            $this->info("Fixed $tmp $location.");
        }
    }
}
