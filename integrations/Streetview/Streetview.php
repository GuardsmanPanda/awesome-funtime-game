<?php

namespace Integrations\Streetview;

use App\Tools\Auth;
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

    public static function findNearbyPanorama(float $lat, float $lng, bool $user_request): bool {
        if (self::findPanorama($lat, $lng, $user_request)) {
            return true;
        }
        return false;
    }


    private static function findPanorama(float $lat, float $lng, bool $user_request): bool {
        $resp = self::query('/metadata', ['location' => $lat . ' ' . $lng]);
        dd($resp);
        return $resp['status'] !== 'ZERO_RESULTS' && self::insertPanorama($resp, $user_request);
    }

    private static function insertPanorama(array $data, bool $user_request):bool {
        DB::insert("
                INSERT INTO panorama (panorama_id, captured_date, panorama_location, added_by_user_id) 
                WHERE panorama_id = ?
            ", [$data['id'], $data['date'].'-01', 'POINT('.$data['location']['lng'].' '.$data['location']['lat'].')', $user_request ? Auth::$user_id : null);
    }

    private static function query(string $path, array $query): array {
        $resp = Http::get(self::$base_url .$path, ['key' => config('settings.streetview_key')] + $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed streetview request: $path -- $query");
        }
        return $resp->json();
    }
}
