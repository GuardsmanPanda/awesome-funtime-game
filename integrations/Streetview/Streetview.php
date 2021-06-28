<?php

namespace Integrations\Streetview;

use App\Tools\Auth;
use RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;

class Streetview {
    private static string $base_url = 'https://maps.googleapis.com/maps/api/streetview';

    public static function findNearbyPanorama(float $lat, float $lng, bool $user_request = false, int $attempts = 30, float $precision = 60): array {
        $id = self::findPanorama($lat, $lng, $user_request);
        $results = [];
        for ($i = 0; $i < $attempts && $id === ''; $i++) {
            $lat2 = $lat + (mt_rand() / mt_getrandmax() - 0.5) / $precision;
            $lng2 = $lng + (mt_rand() / mt_getrandmax() - 0.5) / $precision;
            $id = self::findPanorama($lat2, $lng2, $user_request);
            if ($id === '') {
                $results[] = ['lat' => $lat2, 'lng' => $lng2, 'result' => false];
            } else {
                $tmp = DB::selectOne("
                    SELECT ST_Y(p.panorama_location::geometry) as lat, ST_X(p.panorama_location::geometry) as lng
                    FROM panorama p WHERE p.panorama_id = ?", [$id]);
                $results[] = ['lat' => $tmp->lat, 'lng' => $tmp->lng, 'result' => true];
            }
        }
        return $results;
    }

    private static function findPanorama(float $lat, float $lng, bool $user_request): string {
        $resp = self::query('/metadata', ['location' => $lat . ' ' . $lng]);
        if ($resp['status'] === 'ZERO_RESULTS' || $resp['status'] === 'NOT_FOUND' || !self::insertPanorama($resp, $user_request)) {
            return '';
        }
        return $resp['pano_id'];
    }

    private static function insertPanorama(array $data, bool $user_request): bool {
        try {
            DB::insert("
                INSERT INTO panorama (panorama_id, captured_date, panorama_location, added_by_user_id) VALUES (?, ?, ?, ?)
            ", [$data['pano_id'], $data['date'] . '-01', 'POINT(' . $data['location']['lng'] . ' ' . $data['location']['lat'] . ')', $user_request ? Auth::$user_id : null]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    private static function query(string $path, array $query): array {
        $resp = Http::get(self::$base_url . $path, ['key' => config('settings.streetview_key')] + $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed streetview request: $path -- $query");
        }
        return $resp->json();
    }
}
