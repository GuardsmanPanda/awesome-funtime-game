<?php

namespace Infrastructure\Game;

use Throwable;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

class PanoramaPicker {
    public function __construct(private Game $game) {}

    public function pickPanorama(): string {
        $panorama = null;
        try {
            DB::beginTransaction();
            $map_box = $this->selectMapBox();
            $panorama = $this->selectPanorama("map_box = ?", [$map_box]);
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            report($t);
        }
        return $panorama ?? "CAoSLEFGMVFpcE15NTBwMlhHcURhY2NFbklXeUtrb1pSZjJZZ0lEcUJaRW1iUXhI";
    }

    private function selectMapBox(): int {
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
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL 
            GROUP BY p.map_box
            ORDER BY random() LIMIT 1
        ", [$this->game->id, $this->game->id])->map_box;
    }


    private function selectPanorama(string $where, array $param): string {
        return DB::selectOne("
            SELECT p.panorama_id
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
                AND p3.panorama_id IS NULL AND c2.extended_country_code IS NULL 
            AND $where
            ORDER BY random() LIMIT 1
        ",  array_merge([$this->game->id, $this->game->id], $param))->panorama_id;
    }
}
