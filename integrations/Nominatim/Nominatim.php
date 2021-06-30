<?php

namespace Integrations\Nominatim;

use RuntimeException;
use Illuminate\Support\Facades\Http;

class Nominatim {
    private static string $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2';

    public static function getLocationInformation(float $lat, float $lon): array {
        $res = Http::withHeaders(['User-Agent' => 'Awesome Funtime Game (guardsmanpanda@gmail.com)'])->get(self::$url .'&lat=' . sprintf("%.15f", $lat) . '&lon=' .  sprintf("%.15f", $lon));
        if ($res->failed()) {
            throw new RuntimeException('Error in location lookup: ' .$res->body());
        }
        $j = $res->json();
        if (!array_key_exists('address', $j)) {
            return [
                'country_code' => null,
                'country_name' => null,
                'state_name' => null,
                'city_name' => null,
            ];
        }
        return $j;
    }
}
