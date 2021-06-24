<?php

namespace Integrations\Nominatim;

use RuntimeException;
use Illuminate\Support\Facades\Http;

class Nominatim {
    private static string $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2';

    public static function getLocationInformation(float $lat, float $lon): array {
        $res = Http::withHeaders(['User-Agent' => 'Awesome Funtime Game (guardsmanpanda@gmail.com)'])->get(self::$url .'&lat=' . $lat . '&lon=' .$lon);
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
        $json = $res->json('address');
        return [
            'country_code' => strtoupper($json['country_code'] ?? 'XX'),
            'country_name' => $json['country'] ?? 'Unknown',
            'state_name' => $json['region'] ?? $json['state'] ?? $json['state_district'] ?? $json['county'] ?? 'Unknown',
            'city_name' => $json['city'] ?? $json['municipality'] ?? $json['town'] ?? $json['village'] ?? 'Unknown',
        ];
    }
}
