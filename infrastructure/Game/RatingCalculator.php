<?php

namespace Infrastructure\Game;

use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

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
                WHERE gu.game_id = ?
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
        $tmp = [[
            'rating' => $change + DB::selectOne("SELECT elo_rating FROM realm_user WHERE realm_id = ? AND user_id = ?", [$game->realm_id, $user_id])->elo_rating,
            'change' => $change,
            'timestamp' => $game->ended_at->toIso8601ZuluString(),
            'game_id' => $game->id,
        ]];
        $changed = DB::update("
            UPDATE realm_user 
            SET elo_rating = elo_rating + ?, elo_rating_history = elo_rating_history || ?
            WHERE user_id = ? AND realm_id = ?
        ", [$change, json_encode($tmp, JSON_THROW_ON_ERROR), $user_id, $game->realm_id]);
        if ($changed !== 1) {
            dd($user_id, $game);
        }
    }
}