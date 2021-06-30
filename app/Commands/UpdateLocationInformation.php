<?php

namespace App\Commands;

use App\Models\Panorama;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Integrations\Nominatim\Nominatim;

class UpdateLocationInformation extends Command {
    protected $signature = 'location:update {limit=999}';
    protected $description = 'Update panorama location from Nominatim';

    public function handle(): void {
        $this->reverseLookup();
        $this->reverseRoundUserLookup();
        $this->fixAntarctica();
        $this->assignMapBox();
    }

    private function reverseLookup(): void {
        $panoramas = DB::select("
            SELECT p.panorama_id, ST_X(p.panorama_location::geometry), ST_Y(p.panorama_location::geometry)
            FROM panorama p
            WHERE country_code IS NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($panoramas) === 0) {
            return;
        }
        $this->info("Reverse Panorama Lookup:");
        $this->withProgressBar($panoramas, function ($panorama) {
            $json = Nominatim::getLocationInformation($panorama->st_y, $panorama->st_x);
            $data = Panorama::find($panorama->panorama_id);
            $data->country_code = strtoupper($json['country_code'] ?? 'XX');
            $data->country_name = $json['country'];
            $data->region_name = $json['region'];
            $data->state_name = $json['state'];
            $data->state_district_name = $json['state_district'];
            $data->county_name = $json['county'];
            $data->municipality_name = $json['municipality'];
            $data->city_name = $json['city'];
            $data->town_name = $json['town'];
            $data->village_name = $json['village'];
            $data->save();
            sleep(1);
        });
        $this->newLine();
    }

    private function reverseRoundUserLookup(): void {
        $rus = DB::select("
            SELECT ru.round_id, ru.user_id, ST_X(ru.location::geometry), ST_Y(ru.location::geometry)
            FROM round_user ru
            WHERE ru.location_lookup_at IS NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($rus) === 0) {
            return;
        }
        $this->info("Reverse Round User Lookup:");
        $this->withProgressBar($rus, function ($ru) {
            $json = Nominatim::getLocationInformation($ru->st_y, $ru->st_x);
            DB::update("
                UPDATE round_user 
                SET country_code = ?, country_name = ?, state_name = ?, city_name = ?, location_lookup_at = CURRENT_TIMESTAMP
                WHERE round_id = ? AND user_id = ?
            ", [strtoupper($json['country_code'] ?? 'XX'), $json['country'], $json['state'],  $json['city'], $ru->round_id, $ru->user_id]);
            sleep(1);
        });
        $this->newLine();
    }


    private function fixAntarctica(): void {
        $panoramas = DB::select("
            SELECT p.panorama_id FROM panorama p
            WHERE p.city_name = 'McMurdo Station' AND p.country_code = 'XX'
        ");
        if (count($panoramas) > 0) {
            foreach ($panoramas as $panorama) {
                DB::update("
                    UPDATE panorama SET country_code = 'AQ', country_name = 'Antarctica'
                    WHERE panorama_id = ?
                ", [$panorama->panorama_id]);
            }
            $this->info('Updated ' . count($panoramas) . ' Antarctica panoramas');
        }
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
}
