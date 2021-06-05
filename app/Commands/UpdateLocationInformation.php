<?php

namespace App\Commands;

use App\Models\Panorama;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Integrations\Nominatim\Nominatim;

class UpdateLocationInformation extends Command {
    protected $signature = 'panorama:update-location {limit=999}';
    protected $description = 'Update panorama location from Nominatim';

    public function handle(): void {
        $this->reverseLookup();
        $this->fixAntarctica();
        $this->assignMapBox();
    }

    private function reverseLookup(): void {
        $panoramas = DB::select("
            SELECT p.panorama_id, ST_X(p.panorama_location::geometry), ST_Y(p.panorama_location::geometry)
            FROM panorama p
            WHERE country_code IS NULL AND panorama_location IS NOT NULL
            LIMIT ?
        ", [$this->argument('limit')]);
        if (count($panoramas) === 0) {
            return;
        }
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
