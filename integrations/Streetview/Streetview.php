<?php

namespace Integrations\Streetview;

use App\Tools\Auth;
use RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;

class Streetview {
    private static string $base_url = 'https://maps.googleapis.com/maps/api/streetview';


    public static function findNearbyPanorama(float $lat, float $lng, bool $user_request = false, int $attempts = 30, float $precision = 60): string {
        $id = self::findPanorama($lat, $lng, $user_request);
        for ($i = 0; $i < $attempts && $id === ''; $i++) {
            $lat2 = $lat + (mt_rand() / mt_getrandmax() - 0.5)  /$precision;
            $lng2 = $lng + (mt_rand() / mt_getrandmax() - 0.5) /$precision;
            $id = self::findPanorama($lat2, $lng2, $user_request);
        }
        return $id;
    }


    private static function findPanorama(float $lat, float $lng, bool $user_request): string {
        $resp = self::query('/metadata', ['location' => $lat . ' ' . $lng]);
        if ($resp['status'] === 'ZERO_RESULTS' || !self::insertPanorama($resp, $user_request)) {
            return '';
        }
        return $resp['pano_id'];
    }

    private static function insertPanorama(array $data, bool $user_request): bool {
        try {
            DB::selectOne("
                INSERT INTO panorama (panorama_id, captured_date, panorama_location, added_by_user_id) VALUES (?, ?, ?, ?)
            ", [$data['pano_id'], $data['date'].'-01', 'POINT('.$data['location']['lng'].' '.$data['location']['lat'].')', $user_request ? Auth::$user_id : null]);
        } catch (QueryException) {
            //suppress
            return false;
        }
        return true;
    }

    private static function query(string $path, array $query): array {
        $resp = Http::get(self::$base_url .$path, ['key' => config('settings.streetview_key')] + $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed streetview request: $path -- $query");
        }
        return $resp->json();
    }
}
