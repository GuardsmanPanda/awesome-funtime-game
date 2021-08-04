<?php

namespace Infrastructure\Game;

use Throwable;
use App\Models\Game;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class PanoramaPicker {
    private array $tier_one = ['AT', 'BE', 'CH', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB-ENG', 'GB-SCT', 'GB-WLS', 'GR', 'IE', 'IT', 'LT', 'LV', 'NL', 'NO', 'PL', 'PT', 'SE', 'US'];
    private array $tier_two = ['AL', 'AU', 'BA', 'BG', 'BY', 'CA', 'CN', 'CZ', 'GB-NIR', 'GE', 'HR', 'JP', 'KR', 'LU', 'NZ', 'RS', 'RU', 'SI', 'SK', 'UA', 'VA', 'XK'];
    private array $countries_used = ['XX'];
    private array $user_countries;
    private array $all_countries;
    private array $eligible_users;
    private float $user_country_chance = 0;

    public function __construct(private Game $game) {
        $this->all_countries = Country::pluck('country_code')->toArray();
        $this->eligible_users = $this->getEligibleUsers();
        $tmp = DB::select("
            SELECT
                u.country_code_1, u.country_code_2, u.country_code_3, u.country_code_4
            FROM game_user gu
            LEFT JOIN users u ON u.id = gu.user_id
            WHERE gu.game_id = ? AND  u.country_pick_at > current_timestamp - Interval '7 day'
        ", [$this->game->id]);
        foreach ($tmp as $t) {
            $this->user_country_chance += 3.5;
            $this->user_countries[] = $t->country_code_1 ?? 'XX';
            $this->user_countries[] = $t->country_code_2 ?? 'XX';
            $this->user_countries[] = $t->country_code_3 ?? 'XX';
            $this->user_countries[] = $t->country_code_4 ?? 'XX';
        }
        $this->user_country_chance = min($this->user_country_chance, 40);
        shuffle($this->user_countries);
        shuffle($this->tier_one);
        shuffle($this->tier_two);
        shuffle($this->all_countries);
        shuffle($this->eligible_users);
    }

    public function pickPanorama(int $attempts = 0): array {
        if ($attempts > 15) {
            return  ["CAoSLEFGMVFpcE15NTBwMlhHcURhY2NFbklXeUtrb1pSZjJZZ0lEcUJaRW1iUXhI", 'Error'];
        }
        $pick_strategy = 'None';
        $panorama = null;
        $country = null;
        $user_id = null;
        try {
            if ($country === null && random_int(0, 100) < $this->user_country_chance) {
                $country = $this->pickCountry($this->user_countries);
                $pick_strategy = 'Player choice';
            }
            if ($country === null && random_int(0, 100) < 35) {
                $country = $this->pickCountry($this->tier_one);
                $pick_strategy = 'Tier 1';
            }
            if ($country === null && random_int(0, 100) < 22) {
                $country = $this->pickCountry($this->tier_two);
                $pick_strategy = 'Tier 2';
            }
            if ($country === null && random_int(0, 100) < 30) {
                $user_id = array_pop($this->eligible_users);
                $pick_strategy = 'Random Contributor';
            }

            if ($country === null && $user_id === null && random_int(0, 100) < 40) {
                $country = $this->pickCountry($this->all_countries);
                $pick_strategy = 'Random Country';
            }
            if ($country === null && $user_id === null) {
                $pick_strategy = 'Random Location';
            }
            $map_box = $this->selectMapBox($country, $user_id);
            $panorama = $this->selectPanorama(extended_country_code: $country, map_box: $map_box, user_id: $user_id);
        } catch (Throwable $t) {
            report($t);
        }
        return $panorama === null ?  $this->pickPanorama(++$attempts) : [$panorama, $pick_strategy];
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

    //TODO: increase user delay to 30 days
    //TODO: allow repeats when a year has passed
    private function selectMapBox(string|null $extended_country_code, int|null $user_id = null): int {
        $param = [$this->game->id, $this->game->id];
        $extra_where = "";
        if ($extended_country_code !== null) {
            $extra_where .= " AND p.extended_country_code = ? ";
            $param[] = $extended_country_code;
        }
        if ($user_id !== null) {
            $extra_where .= " AND p.added_by_user_id = ? ";
            $param[] = $user_id;
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
                p.jpg_name IS NOT NULL AND p.extended_country_code IS NOT NULL
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL 
                $extra_where
            GROUP BY p.map_box
            ORDER BY random() LIMIT 1
        ", $param)?->map_box;
    }


    private function selectPanorama(string $extended_country_code = null, int $map_box = -1, int|null $user_id = null): string {
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
        if ($user_id !== null) {
            $extra_where .= " AND p.added_by_user_id = ? ";
            $param[] = $user_id;
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
                p.jpg_name IS NOT NULL AND p.extended_country_code IS NOT NULL
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL
                $extra_where
            ORDER BY random() LIMIT 1
        ",  $param);

        if ($res !== null) {
            $this->countries_used[] = $res->extended_country_code;
        }
        return $res?->panorama_id;
    }

    private function getEligibleUsers(): array {
        $tmp = DB::select("
            SELECT DISTINCT
                p.added_by_user_id
            FROM panorama p
            LEFT JOIN (
                SELECT r.id, r.panorama_id
                FROM game_user gu
                LEFT JOIN round_user ru ON ru.user_id = gu.user_id
                LEFT JOIN round r ON r.id = ru.round_id
                WHERE gu.game_id = ?
                GROUP BY r.id
            ) p3 ON p3.panorama_id = p.panorama_id
            WHERE p.added_by_user_id IS NOT NULL AND p3.panorama_id IS NULL
            GROUP BY p.added_by_user_id
        ", [$this->game->id]);
        return array_map(static function ($t) { return $t->added_by_user_id;}, $tmp);
    }
}
