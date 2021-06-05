<?php

namespace Integrations\Streetview;

use RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Streetview {
    private static string $base_url = 'https://maps.googleapis.com/maps/api/streetview';

    public static function panoramaUpdate() {
        $to_update = DB::select("SELECT * FROM panorama WHERE captured_date IS NULL LIMIT 500");
        $res = [];
        foreach ($to_update as $pano) {
            $resp = self::query('/metadata', ['pano' => $pano->panorama_id]);
            $res[] = $resp;
            if ($resp['status'] === 'ZERO_RESULTS') {
                DB::delete("DELETE FROM panorama WHERE panorama_id = ?", [$pano->panorama_id]);
                continue;
            }
            DB::update("
                UPDATE panorama
                SET captured_date = ?, panorama_location = ?
                WHERE panorama_id = ?
            ", [$resp['date'].'-01', 'POINT('.$resp['location']['lng'].' '.$resp['location']['lat'].')',$pano->panorama_id]);
            usleep(100_000);
        }
        return $res;
    }

    private static function query(string $path, array $query): array {
        $resp = Http::get(self::$base_url .$path, ['key' => config('settings.streetview_key')] + $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed streetview request: $path -- $query");
        }
        return $resp->json();
    }
}
