<?php

namespace Infrastructure\Game;

use Throwable;
use App\Models\Game;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class PanoramaPicker {
    private array $tier_one = ['AT', 'BE', 'CH', 'DE', 'DK', 'EE', 'EL', 'ES', 'FI', 'FR', 'GR', 'IE', 'IT', 'LT', 'LV', 'NL', 'NO', 'PL', 'PT', 'SE', 'UK', 'US'];
    private array $tier_two = ['AL', 'AU', 'BA', 'BG', 'BY', 'CA', 'CN', 'CZ', 'GE', 'HR', 'JP', 'KR', 'LU', 'NZ', 'RS', 'RU', 'SI', 'SK', 'UA', 'VA', 'XK'];
    private array $countries_used = ['XX'];
    private array $user_countries;
    private array $all_countries;
    private float $user_country_chance = 0;

    public function __construct(private Game $game) {
        $this->all_countries = Country::pluck('country_code')->toArray();
        $tmp = DB::select("
            SELECT
                u.country_code_1, u.country_code_2, u.country_code_3, u.country_code_4,
                u.country_pick_at > current_timestamp - Interval '7 day' as recent_pick
            FROM game_user gu
            LEFT JOIN users u ON u.id = gu.user_id
            WHERE gu.game_id = ?
        ", [$this->game->id]);
        foreach ($tmp as $t) {
            if (!$t->recent_pick) {
                continue;
            }
            $this->user_country_chance += 5;
            $this->user_countries[] = $t->country_code_1 ?? 'XX';
            $this->user_countries[] = $t->country_code_2 ?? 'XX';
            $this->user_countries[] = $t->country_code_3 ?? 'XX';
            $this->user_countries[] = $t->country_code_4 ?? 'XX';
        }
        $this->user_country_chance = min($this->user_country_chance, 50);
        shuffle($this->user_countries);
        shuffle($this->tier_one);
    }

    public function pickPanorama(int $attempts = 0): string {
        if ($attempts > 15) {
            return  "CAoSLEFGMVFpcE15NTBwMlhHcURhY2NFbklXeUtrb1pSZjJZZ0lEcUJaRW1iUXhI";
        }
        $panorama = null;
        $map_box = null;
        $country = null;
        try {
            if ($country === null && random_int(0, 100) < $this->user_country_chance) {
                $country = $this->pickCountry($this->user_countries);
            }
            if ($country === null && random_int(0, 100) < 25) {
                $country = $this->pickCountry($this->tier_one);
            }
            if ($country === null && random_int(0, 100) < 25) {
                $country = $this->pickCountry($this->tier_two);
            }
            if ($country === null && random_int(0, 100) < 40) {
                $country = $this->pickCountry($this->all_countries);
            }
            $map_box = $this->selectMapBox($country);
            $panorama = $this->selectPanorama(extended_country_code: $country, map_box: $map_box);
        } catch (Throwable $t) {
            report($t);
        }
        return $panorama ?? $this->pickPanorama(++$attempts);
    }

    private function pickCountry(array &$country_list): null|string {
        $country = null;
        while ($country === null && count($country_list) > 0) {
            $t = array_pop($country_list);
            if (!in_array($t, $this->countries_used, true)) {
                $country = $t;
            }
        }
        return $country;
    }


    private function selectMapBox(string|null $extended_country_code): int {
        $param = [$this->game->id, $this->game->id];
        $extra_where = "";
        if ($extended_country_code !== null) {
            $extra_where .= " AND p.extended_country_code = ? ";
            $param[] = $extended_country_code;
        }
        return DB::selectOne("
            SELECT p.map_box FROM panorama p
            LEFT JOIN (
                SELECT pp.extended_country_code
                FROM round rr
                LEFT JOIN panorama pp ON pp.panorama_id = rr.panorama_id
                WHERE rr.game_id = ?
            ) as c2 ON c2.extended_country_code = p.extended_country_code
            LEFT JOIN (
                SELECT r.id, r.panorama_id
                FROM game_user gu
                LEFT JOIN round_user ru ON ru.user_id = gu.user_id
                LEFT JOIN round r ON r.id = ru.round_id
                WHERE gu.game_id = ?
                GROUP BY r.id
            ) p3 ON p3.panorama_id = p.panorama_id
            WHERE 
                p.jpg_name IS NOT NULL AND p.captured_date > '2011-01-01' AND p.extended_country_code IS NOT NULL
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL $extra_where
            GROUP BY p.map_box
            ORDER BY random() LIMIT 1
        ", $param)?->map_box;
    }


    private function selectPanorama(string $extended_country_code = null, int $map_box = -1): string {
        $param = [$this->game->id, $this->game->id];
        $extra_where = "";
        if ($extended_country_code !== null) {
            $extra_where .= " AND p.extended_country_code = ? ";
            $param[] = $extended_country_code;
        }
        if ($map_box !== -1) {
            $extra_where .= " AND p.map_box = ?";
            $param[] = $map_box;
        }
        $res = DB::selectOne("
            SELECT p.panorama_id, p.extended_country_code
            FROM panorama p
            LEFT JOIN (
                SELECT pp.extended_country_code
                FROM round rr
                LEFT JOIN panorama pp ON pp.panorama_id = rr.panorama_id
                WHERE rr.game_id = ?
            ) as c2 ON c2.extended_country_code = p.extended_country_code
            LEFT JOIN (
                SELECT r.id, r.panorama_id
                FROM game_user gu
                LEFT JOIN round_user ru ON ru.user_id = gu.user_id
                LEFT JOIN round r ON r.id = ru.round_id
                WHERE gu.game_id = ?
                GROUP BY r.id
            ) p3 ON p3.panorama_id = p.panorama_id
            WHERE 
                p.jpg_name IS NOT NULL AND p.captured_date > '2011-01-01' AND p.extended_country_code IS NOT NULL
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL $extra_where
            ORDER BY random() LIMIT 1
        ",  $param);

        if ($res !== null) {
            $this->countries_used[] = $res->extended_country_code;
        }
        return $res?->panorama_id;
    }
}
