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
        $this->assignMapBox();
    }

    private function reverseLookup(): void {
        $panoramas = DB::select("
            SELECT p.panorama_id, ST_X(p.panorama_location::geometry), ST_Y(p.panorama_location::geometry)
            FROM panorama p
            WHERE p.country_code IS NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($panoramas) === 0) {
            return;
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
