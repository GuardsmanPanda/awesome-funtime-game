<?php

namespace App\Commands;

use App\Models\User;
use App\Models\Panorama;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Integrations\Nominatim\Nominatim;

class UpdateLocationInformation extends Command {
    protected $signature = 'location:update {limit=60}';
    protected $description = 'Update panorama location from Nominatim';

    public function handle(): void {
        $count = $this->reverseLookup();
        $count += $this->reverseRoundUserLookup();
        $this->assignMapBox();
        if ($count === 0) {
            return;
        }
        $this->fixAntarctica();
        $this->fixBoth("extended_country_code = country_code WHERE extended_country_code IS NULL", 'remaining');
        $this-> fixCountries();
    }

    private function reverseLookup(): int {
        $panoramas = DB::select("
            SELECT p.panorama_id, ST_X(p.panorama_location::geometry), ST_Y(p.panorama_location::geometry)
            FROM panorama p
            WHERE p.country_code IS NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($panoramas) === 0) {
            return 0;
        }
        $this->info("Reverse Panorama Lookup:");
        $this->withProgressBar($panoramas, function ($panorama) {
            $j = Nominatim::getLocationInformation($panorama->st_y, $panorama->st_x);
            $data = Panorama::find($panorama->panorama_id);
            $data->country_code = strtoupper($j['address']['country_code'] ?? 'XX');
            $data->country_name = $j['address']['country'] ?? null;
            $data->region_name = $j['address']['region'] ?? null;
            $data->state_name = $j['address']['state'] ?? null;
            $data->state_district_name = $j['address']['state_district'] ?? null;
            $data->county_name = $j['address']['county'] ?? null;
            $data->city_name = $j['address']['city'] ?? $j['address']['municipality'] ?? $j['address']['town'] ?? $j['address']['village'] ?? null;
            $data->save();
            sleep(1);
        });
        $this->newLine();
        return count($panoramas);
    }

    private function reverseRoundUserLookup(): int {
        $rus = DB::select("
            SELECT ru.round_id, ru.user_id, ST_X(ru.location::geometry), ST_Y(ru.location::geometry)
            FROM round_user ru
            LEFT JOIN round r on ru.round_id = r.id
            WHERE ru.location_lookup_at IS NULL AND r.round_end_at < CURRENT_TIMESTAMP
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($rus) === 0) {
            return 0;
        }
        $this->info("Reverse Round User Lookup:");

        $updated_users = [];

        $this->withProgressBar($rus, function ($ru) use (&$updated_users) {
            $updated_users[] = $ru->user_id;
            $j = Nominatim::getLocationInformation($ru->st_y, $ru->st_x);
            DB::update("
                UPDATE round_user 
                SET country_code = ?, country_name = ?, state_name = ?, city_name = ?,
                    location_lookup_at = CURRENT_TIMESTAMP,
                    region_name = ?, state_district_name = ?, county_name = ?
                WHERE round_id = ? AND user_id = ?
            ", [
                strtoupper($j['address']['country_code'] ?? 'XX'),
                $j['address']['country'] ?? null,
                $j['address']['state'] ?? null,
                $j['address']['city'] ?? $j['address']['municipality'] ?? $j['address']['town'] ?? $j['address']['village'] ?? null,
                $j['address']['region'] ?? null,
                $j['address']['state_district'] ?? null,
                $j['address']['county'] ?? null,
                $ru->round_id, $ru->user_id]);
            sleep(1);
        });
        $this->newLine();
        User::whereIn('id', array_unique($updated_users))->update(['achievement_refresh_needed' => true]);
        return count($rus);
    }

    private function assignMapBox(): void {
        $count = DB::selectOne("
            SELECT COUNT(*) FROM panorama WHERE map_box IS NULL
        ")->count;
        if ($count > 0) {
            DB::update("
                UPDATE panorama SET map_box = floor((ST_Y(panorama_location::geometry)+90)*2)*180*2 + floor(ST_X(panorama_location::geometry)*2)
                WHERE map_box IS NULL");
            $this->info("Assigned " . $count . ' map boxes');
        }
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

        $this->fixBoth("extended_country_code = 'VI' WHERE state_name = 'United States Virgin Islands' AND extended_country_code = 'US'", 'United States Virgin Islands');
        $this->fixBoth("extended_country_code = 'MP' WHERE state_name = 'Northern Mariana Islands' AND extended_country_code = 'US'", 'Northern Mariana Islands	');
        $this->fixBoth("extended_country_code = 'AS' WHERE state_name = 'American Samoa' AND extended_country_code = 'US'", 'American Samoa');
        $this->fixBoth("extended_country_code = 'PR' WHERE state_name = 'Puerto Rico' AND extended_country_code = 'US'", 'Puerto Rico');
        $this->fixBoth("extended_country_code = 'GU' WHERE state_name = 'Guam' AND extended_country_code = 'US'", 'Guam');

        $this->fixBoth("extended_country_code = 'CX' WHERE city_name = 'Shire of Christmas Island' AND extended_country_code = 'AU'", 'Christmas Island');
        $this->fixBoth("extended_country_code = 'CC' WHERE city_name = 'Shire of Cocos Islands' AND extended_country_code = 'AU'", 'Cocos Island');

        $this->fixBoth("extended_country_code = 'HK' WHERE state_name = '香港 Hong Kong' AND extended_country_code = 'CN'", 'Hong Kong');
        $this->fixBoth("extended_country_code = 'MO' WHERE state_name = '澳門 Macau' AND extended_country_code = 'CN'", 'Macau');

        $this->fixBoth("extended_country_code = 'SJ' WHERE county_name = 'Jan Mayen' AND extended_country_code = 'NO'", 'Svalbard');
        $this->fixBoth("extended_country_code = 'SJ' WHERE region_name = 'Svalbard' AND extended_country_code = 'NO'", 'Svalbard');

        $this->fixBoth("extended_country_code = 'GB-NIR' WHERE state_name = 'Northern Ireland' AND extended_country_code = 'GB'", 'Northern Ireland');
        $this->fixBoth("extended_country_code = 'GB-WLS' WHERE state_name = 'Cymru / Wales' AND extended_country_code = 'GB'", 'Cymru / Wales');
        $this->fixBoth("extended_country_code = 'GB-SCT' WHERE state_name = 'Scotland' AND extended_country_code = 'GB'", 'Scotland');
        $this->fixBoth("extended_country_code = 'GB-ENG' WHERE state_name = 'England' AND extended_country_code = 'GB'", 'England');

        $this->fixBoth("extended_country_code = 'AX' WHERE (county_name = 'Åland' OR county_name = 'Landskapet Åland') AND extended_country_code = 'FI'", 'Åland');
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
