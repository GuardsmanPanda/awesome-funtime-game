<?php

namespace App\Commands;

use App\Models\LocationCities500;
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
        $this->reverseCites();
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
            //$this->info(json_encode($panorama, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
            $res = Nominatim::getLocationInformation($panorama->st_y, $panorama->st_x);
            //$this->info(json_encode($res, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
            $data = Panorama::find($panorama->panorama_id);
            $data->country_code = $res['country_code'];
            $data->country_name = $res['country_name'];
            $data->state_name = $res['state_name'];
            $data->city_name = $res['city_name'];
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
            $res = Nominatim::getLocationInformation($ru->st_y, $ru->st_x);
            DB::update("
                UPDATE round_user 
                SET country_code = ?, country_name = ?, state_name = ?, city_name = ?, location_lookup_at = CURRENT_TIMESTAMP
                WHERE round_id = ? AND user_id = ?
            ", [$res['country_code'], $res['country_name'], $res['state_name'],  $res['city_name'], $ru->round_id, $ru->user_id]);
            sleep(1);
        });
        $this->newLine();
    }


    private function reverseCites(): void {
        $loc = DB::select("
            SELECT lc.id, lc.lat, lc.lng
            FROM location_cities_500 lc
            WHERE country_code IS NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($loc) === 0) {
            return;
        }
        $this->info("Reverse Cities:");
        $this->withProgressBar($loc, function ($lo) {
            $res = Nominatim::getLocationInformation($lo->lat, $lo->lng);
            $data = LocationCities500::find($lo->id);
            $data->country_code = $res['country_code'] ?? 'XX';
            $data->country_name = $res['country_name'];
            $data->state_name = $res['state_name'];
            $data->city_name = $res['city_name'];
            $data->save();
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
