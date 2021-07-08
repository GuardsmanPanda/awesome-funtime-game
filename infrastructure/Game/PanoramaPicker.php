<?php

namespace Infrastructure\Game;

use Throwable;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

class PanoramaPicker {
    private array $countries_used = ['XX'];
    private array $user_countries;
    private float $user_country_chance = 0;

    public function __construct(private Game $game) {
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
            $this->user_country_chance += 8;
            $this->user_countries[] = $t->country_code_1 ?? 'XX';
            $this->user_countries[] = $t->country_code_2 ?? 'XX';
            $this->user_countries[] = $t->country_code_3 ?? 'XX';
            $this->user_countries[] = $t->country_code_4 ?? 'XX';
        }
        $this->user_country_chance = min($this->user_country_chance, 70);
        shuffle($this->user_countries);
    }

    public function pickPanorama(int $attempts = 0): string {
        if ($attempts > 10) {
            return  "CAoSLEFGMVFpcE15NTBwMlhHcURhY2NFbklXeUtrb1pSZjJZZ0lEcUJaRW1iUXhI";
        }
        $panorama = null;
        $map_box = null;
        $country = null;
        try {
            if (random_int(0, 100) < $this->user_country_chance) {
                while ($country === null && count($this->user_countries) > 0) {
                    $t = array_pop($this->user_countries);
                    if (!in_array($t, $this->countries_used, true)) {
                        $country = $t;
                    }
                }
            }
            $map_box = $this->selectMapBox($country);
            $panorama = $this->selectPanorama(extended_country_code: $country, map_box: $map_box);
        } catch (Throwable $t) {
            report($t);
        }
        return $panorama ?? $this->pickPanorama(++$attempts);
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
