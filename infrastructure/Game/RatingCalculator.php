<?php

namespace Infrastructure\Game;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\GameUser;
use Illuminate\Support\Facades\DB;

class RatingCalculator {
    public static function calculate(int $realm): void {
        $games = Game::where('realm_id', '=', $realm)
            ->where('ele_calculated', '=', false)
            ->orderBy('ended_at')
            ->get();
        foreach ($games as $game) {
            $players = DB::select("
                SELECT gu.user_id, ru.elo_rating
                FROM game_user gu
                LEFT JOIN realm_user ru ON ru.user_id = gu.user_id AND ru.realm_id = ?
                WHERE gu.game_id = ?
                ORDER BY gu.points_total DESC, random()
            ", [$realm, $game->id]);
            $last_player = null;
            foreach ($players as $player) {
                if ($last_player !== null) {

                }
                $last_player = $player;
            }

            $game->elo_calculated = true;
            $game->save();
        }

    }

    private static function updateRating(int $user_id, float $change, Carbon $timeStamp, int $game_id): void {

    }
}