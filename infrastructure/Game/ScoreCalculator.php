<?php

namespace Infrastructure\Game;

use App\Models\Round;
use Illuminate\Support\Facades\DB;

class ScoreCalculator {
    public static function scoreRound(Round $round): void {
        try {
            DB::beginTransaction();
            DB::update("
            UPDATE round_user ru SET
                distance = ST_distance(ru.location, (
                    SELECT p.panorama_location FROM panorama p WHERE p.panorama_id = ?
                )),
                closest_country_code = (
                    SELECT close.extended_country_code  FROM ((
                        SELECT p2.extended_country_code, ST_distance(ru.location, p2.panorama_location) as distance FROM panorama p2
                        WHERE p2.extended_country_code IS NOT NULL
                        ORDER BY ru.location <-> p2.panorama_location
                        LIMIT 1
                        ) 
                        UNION (
                        SELECT r3.extended_country_code, ST_distance(ru.location, r3.location) as distance FROM round_user r3
                        WHERE r3.extended_country_code IS NOT NULL
                        ORDER BY ru.location <-> r3.location
                        LIMIT 1
                        ) 
                    ORDER BY distance LIMIT 1) as close
                ),
                closest_country_code_distance = (
                    SELECT close.distance  FROM ((
                        SELECT p2.extended_country_code, ST_distance(ru.location, p2.panorama_location) as distance FROM panorama p2
                        WHERE p2.extended_country_code IS NOT NULL
                        ORDER BY ru.location <-> p2.panorama_location
                        LIMIT 1
                        ) 
                        UNION (
                        SELECT r3.extended_country_code, ST_distance(ru.location, r3.location) as distance FROM round_user r3
                        WHERE r3.extended_country_code IS NOT NULL
                        ORDER BY ru.location <-> r3.location
                        LIMIT 1
                        ) 
                    ORDER BY distance LIMIT 1) as close
                )
            WHERE ru.round_id = ?
        ", [$round->panorama_id, $round->id]);

            DB::update("
            UPDATE round_user ru SET
                points = 100 * pow(0.90, rr_rank.round_rank - 1)
                    + CASE WHEN ru.closest_country_code = p.extended_country_code THEN 20 ELSE 0 END,
                is_correct_country = ru.closest_country_code = p.extended_country_code
            FROM panorama p, (SELECT
                        ru2.round_id, ru2.user_id,
                        rank() OVER (PARTITION BY ru2.round_id ORDER BY ru2.distance) as round_rank
                    FROM round_user ru2
                    WHERE round_id = ?
                    ) rr_rank
            WHERE
                p.panorama_id = ? AND ru.round_id = ?
                AND rr_rank.round_id = ru.round_id
                AND rr_rank.user_id = ru.user_id
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
