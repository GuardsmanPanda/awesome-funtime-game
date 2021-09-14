<?php

namespace Infrastructure\Game;

use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Support\Facades\DB;
use App\Models\RealmUserRatingHistory;

class RatingCalculator {
    public static function calculate(int $realm_id): void {
        try {
            DB::beginTransaction();
            $games = Game::where('realm_id', '=', $realm_id)
                ->where('elo_calculated', '=', false)
                ->orderBy('ended_at')
                ->get();
            foreach ($games as $game) {
                $players = DB::select("
                SELECT gu.user_id, ru.elo_rating, RANK() OVER (ORDER BY gu.points_total DESC) as rank 
                FROM game_user gu
                LEFT JOIN realm_user ru ON ru.user_id = gu.user_id AND ru.realm_id = ?
                LEFT JOIN round r ON r.game_id = gu.game_id AND r.round_number = 1
                LEFT JOIN round_user rru ON rru.round_id = r.id AND rru.user_id = gu.user_id
                WHERE gu.game_id = ? AND ru.user_id IS NOT NULL
                ORDER BY gu.points_total DESC, random()
            ", [$realm_id, $game->id]);

                $elo_calc = new CalculateElo();
                foreach ($players as $player) {
                    $elo_calc->addPlayer( $player->user_id, $player->rank, $player->elo_rating);
                }
                $elo_calc->calculateELOs();
                foreach ($players as $player) {
                    self::updateRating($player->user_id, $elo_calc->getELOChange($player->user_id), $game);
                }
                $game->elo_calculated = true;
                $game->save();
            }
            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
        }
    }

    private static function updateRating(int $user_id, int $change, Game $game): void {
        $changed = DB::selectOne("
            UPDATE realm_user 
            SET elo_rating = elo_rating + ?
            WHERE user_id = ? AND realm_id = ?
            RETURNING elo_rating
        ", [$change, $user_id, $game->realm_id]);
        $history = new RealmUserRatingHistory();
        $history->user_id = $user_id;
        $history->game_id = $game->id;
        $history->rating_change = $change;
        $history->rating_after = $changed->elo_rating;
        $history->save();
    }
}