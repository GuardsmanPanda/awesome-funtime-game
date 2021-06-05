<?php

namespace Infrastructure\Game;

use App\Models\Round;
use Illuminate\Support\Facades\DB;

class ScoreCalculator {
    public static function scoreRound(Round $round): void {
        //TODO create gist index and see perf improvement
        try {
            DB::beginTransaction();
            DB::update("
            UPDATE round_user ru SET
                distance = ST_distance(ru.location, (
                    SELECT p.panorama_location FROM panorama p WHERE p.panorama_id = ?
                )),
                closest_panorama_id = (
                    SELECT p2.panorama_id FROM panorama p2
                    WHERE p2.country_code IS NOT NULL
                    ORDER BY ru.location <-> p2.panorama_location
                    LIMIT 1
                )
            WHERE ru.round_id = ?
        ", [$round->panorama_id, $round->id]);

            DB::update("
            UPDATE round_user ru SET
                points = 100 * pow(0.90, rr_rank.round_rank - 1)
                    + CASE WHEN p2.country_code = p.country_code THEN 20 ELSE 0 END,
                is_correct_country = p2.country_code = p.country_code
            FROM panorama p, panorama p2, (SELECT
                        ru2.round_id, ru2.user_id,
                        rank() OVER (PARTITION BY ru2.round_id ORDER BY ru2.distance) as round_rank
                    FROM round_user ru2
                    WHERE round_id = ?
                    ) rr_rank
            WHERE
                p.panorama_id = ? AND ru.round_id = ? AND p2.panorama_id = ru.closest_panorama_id
                AND rr_rank.round_id = ru.round_id AND rr_rank.user_id = ru.user_id
        ", [$round->id, $round->panorama_id, $round->id]);

            DB::insert("
                INSERT INTO game_user (game_id, user_id, points_total)
                SELECT r.game_id, ru.user_id, ru.points FROM round_user ru
                LEFT JOIN round r ON r.id = ru.round_id
                WHERE ru.round_id = ?
                ON CONFLICT (game_id, user_id) DO UPDATE SET
                    points_total = game_user.points_total + excluded.points_total
        ", [$round->id]);
            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            report($t);
        }
    }
}
