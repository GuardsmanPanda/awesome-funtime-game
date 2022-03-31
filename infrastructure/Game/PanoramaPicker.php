<?php

namespace Infrastructure\Game;

use Throwable;
use App\Models\Game;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class PanoramaPicker {
    private array $tier_one = ['AT', 'BE', 'CH', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB-ENG', 'GB-SCT', 'GB-WLS', 'GR', 'IE', 'IT', 'LT', 'LV', 'NL', 'NO', 'PL', 'PT', 'SE', 'US'];
    private array $tier_two = ['AL', 'AU', 'BA', 'BG', 'BY', 'CA', 'CN', 'CZ', 'GB-NIR', 'GE', 'HR', 'JP', 'KR', 'LU', 'NZ', 'RS', 'RU', 'SI', 'SK', 'UA', 'VA', 'XK'];
    private array $tier_filler = [
        'CA', 'US', 'MX', 'CU',
        'AR', 'CL', 'UY', 'BR', 'PY', 'PE', 'BO', 'EC', 'CO', 'VE',
        'AU', 'NZ', 'ID', 'MY', 'BN', 'SG', 'PH',
        'ZA', 'BW', 'KE', 'UG', 'NG', 'GH', 'SN', 'MA', 'TN', 'EG', 'ZM', 'ZW',
        'RU', 'IN', 'MN', 'CN', 'JP', 'KR', 'TW', 'VN', 'LA', 'NP', 'BT', 'KH', 'TH', 'MM', 'BD', 'MO', 'HK',
        'PK', 'KZ', 'IR', 'IQ', 'AE', 'KW', 'BH', 'QA', 'SA', 'JO', 'PS', 'IL', 'LB', 'TR', 'GE', 'AZ', 'AM',
        'GL', 'IS', 'NO', 'SE', 'FI', 'FO', 'AX', 'ET', 'LV', 'EE', 'DK', 'GB-NIR', 'GB-ENG', 'GB-SCT', 'GB-WLS', 'IE', 'PT', 'ES', 'FR', 'LU', 'BE', 'NL', 'AD', 'MC', 'DE', 'CH', 'AT', 'CZ', 'SK', 'PL', 'LI', 'VA', 'BY', 'UA', 'MD', 'HU', 'RO', 'SI', 'HR', 'BA', 'ME', 'RS', 'BG', 'GR', 'AL', 'XK', 'MK',
    ];
    private array $countries_used = ['XX'];
    private array $user_countries = ['XX'];
    private array $all_countries;
    private array $eligible_users;
    private float $user_country_chance = 0;
    private int $tier_one_chance = 15;
    private int $tier_two_chance = 10;
    private array $sneaky = [
        'CAoSLEFGMVFpcE93TF9QXzF6OW9uaVBvVUwzT0tDV0RhTlZ2anNqdWJhZ3dORzdk', // 
        'CAoSLEFGMVFpcE9Ud2Z5MTRDODA4RHNMejh1N2RueS1ReTVnV1RxRUJXb2FPRGc3', //
        'CAoSLEFGMVFpcFBacXRUemVfYUMtSnlsc0FNMFh1cTNYN3ZJb0s1c0k1NzUwUjFi', //
        'CAoSK0FGMVFpcFB0NWJHNmMwaDVSWjUtTW1pTU1aclBORlN6VHpYQUJvZkZJQTQ.', //
        'CAoSLEFGMVFpcE9RbnB0VkFpNjNVU3hyRW1YbnpqRDNwdkR0OVg3YlU3d1dEOGtv.', //
        'CAoSK0FGMVFpcE9tM2M1LV9BWXM5UTZJSlEwaWc5SUVnb3hTVmtyQjVra2RJek0.', //
        'CAoSLEFGMVFpcE1pX2lvUnI5eWdYTmZzRjlmYlVRcTZscnh6Y2cteE1lcl84U25p', //
        'CAoSLEFGMVFpcE1QbjQzVW04RHlMQmMxOHBhbmZqVFRNSWtDbkFrLW9HdGROSXZV', //
    ];

    public function __construct(private Game $game) {
        if ($this->game->id !== 758) {
            $this->sneaky = [];
        }
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
            $this->user_country_chance += 3.1;
            $this->user_countries[] = $t->country_code_1 ?? 'XX';
            $this->user_countries[] = $t->country_code_2 ?? 'XX';
            $this->user_countries[] = $t->country_code_3 ?? 'XX';
            $this->user_countries[] = $t->country_code_4 ?? 'XX';
        }
        $this->user_country_chance = min($this->user_country_chance, 40);

        $tmp = DB::select("
            SELECT p.extended_country_code
            FROM round r
            LEFT JOIN game g on r.game_id = g.id
            LEFT JOIN panorama p on r.panorama_id = p.panorama_id
            WHERE g.realm_id = ?
            ORDER BY r.created_at DESC LIMIT 80
        ", [$this->game->realm_id]);
        $tmp_delete = [];
        foreach ($tmp as $tt) {
            $tmp_delete[] = $tt->extended_country_code;
        }
        if ($this->game->realm_id === 2) {
            $this->tier_one_chance = 25;
            $this->tier_two_chance = 20;
        }

        $this->tier_filler = array_filter($this->tier_filler, static function ($ele) use ($tmp_delete) {return !in_array($ele, $tmp_delete, true);});
        shuffle($this->user_countries);
        shuffle($this->tier_one);
        shuffle($this->tier_two);
        shuffle($this->tier_filler);
        shuffle($this->all_countries);
        shuffle($this->eligible_users);
    }

    public function pickPanorama(int $attempts = 0): array {
        if ($attempts > 15) {
            return  ["CAoSLEFGMVFpcE15NTBwMlhHcURhY2NFbklXeUtrb1pSZjJZZ0lEcUJaRW1iUXhI", 'Error'];
        }
        if (count($this->sneaky) > 0) {
            return [array_pop($this->sneaky), 'Generaxion'];
        }
        $pick_strategy = 'None';
        $panorama = null;
        $country = null;
        $map_box = -1;
        $user_id = null;
        try {
            if ($country === null && random_int(0, 100) < $this->user_country_chance) {
                $country = $this->pickCountry($this->user_countries);
                $pick_strategy = 'Player choice';
            }
            if ($country === null && random_int(0, 100) < $this->tier_one_chance) {
                $country = $this->pickCountry($this->tier_one);
                $pick_strategy = 'Tier 1';
            }
            if ($country === null && random_int(0, 100) < $this->tier_two_chance) {
                $country = $this->pickCountry($this->tier_two);
                $pick_strategy = 'Tier 2';
            }
            if ($country === null && random_int(0, 100) < 54) {
                $country = $this->pickCountry($this->tier_filler);
                $pick_strategy = 'Filler';
            }
            if ($country === null && random_int(0, 100) < 30) {
                $user_id = array_pop($this->eligible_users);
                $pick_strategy = 'Random Contributor';
            }

            if ($country === null && $user_id === null && random_int(0, 100) < 30) {
                $country = $this->pickCountry($this->all_countries);
                $pick_strategy = 'Random Country';
            }
            if ($country === null && $user_id === null) {
                $pick_strategy = 'Random Location';
            }
            if (random_int(0, 100) < 80) {
                $map_box = $this->selectMapBox($country, $user_id);
            }
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
                p.jpg_name IS NOT NULL AND p.extended_country_code IS NOT NULL AND p.extended_country_code != 'XX'
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
                p.jpg_name IS NOT NULL AND p.extended_country_code IS NOT NULL AND p.extended_country_code != 'XX'
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL
                $extra_where
            ORDER BY random() + CASE WHEN p.added_by_user_id IS NOT NULL THEN 0.015 ELSE 0 END  LIMIT 1
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
