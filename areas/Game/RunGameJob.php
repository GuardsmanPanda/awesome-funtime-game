<?php

namespace Areas\Game;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Support\Facades\DB;
use Infrastructure\Game\ScoreCalculator;
use Infrastructure\Game\RatingCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Infrastructure\Game\PanoramaPicker;

class RunGameJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $game_id, private int $countdown) { }

    public function handle(): void {
        $game = Game::find($this->game_id);
        $game->game_start_at = Carbon::now()->addMinutes($this->countdown);
        $game->save();
        sleep($this->countdown * 60 - 3);

        $picker = new PanoramaPicker($game);
        for ($i = 1; $i <= $game->round_count; $i++) {
            $round = new Round();
            $round->round_number = $i;
            $round->game_id = $game->id;

            $arr = $picker->pickPanorama();
            $round->panorama_id = $arr[0];
            $round->panorama_pick_strategy = $arr[1];

            $round->country_fact_id = DB::selectOne("
                SELECT cf.id
                FROM panorama p, country_fact cf
                WHERE p.panorama_id = ? AND cf.country_code = p.extended_country_code
                ORDER BY random() LIMIT 1
            ", [$round->panorama_id])?->id;
            $round->round_end_at = Carbon::now()->addSeconds($game->round_time);
            $round->save();

            DB::insert("
                INSERT INTO round_user (round_id, user_id)
                SELECT ?, user_id FROM game_user WHERE game_id = ?
            ", [$round->id, $game->id]);

            $game->current_round_id = $round->id;
            $game->is_round_active = true;
            $game->next_round_at = null;
            $game->current_round = $i;
            $game->save();

            sleep($game->round_time);
            $game->is_round_active = false;
            $game->save();

            ScoreCalculator::scoreRound($round);
            $game->next_round_at = Carbon::now()->addSeconds(21);
            $game->save();
            sleep(19);
        }
        $game->ended_at = Carbon::now();
        $game->is_queued = false;
        $game->save();

        //REALM RATING UPDATE
        RatingCalculator::calculate($game->realm_id);

        // ACHIEVEMENT UPDATES
        DB::update("
            UPDATE users u SET
            achievement_refresh_needed = true
            WHERE EXISTS(SELECT * FROM game_user gu WHERE gu.user_id = u.id AND gu.game_id = ?)
        ", [$this->game_id]);

        if ($game->round_count >= 5) {
            $players = DB::select("
            SELECT
                gu.user_id,
                RANK() OVER (ORDER BY gu.points_total DESC) AS rank
            FROM game_user gu
            WHERE gu.game_id = ?
            ORDER BY rank
        ", [$game->id]);
            foreach ($players as $player) {
                if ($player->rank > 3) {
                    break;
                }
                if ($player->rank === 1) {
                    DB::update("UPDATE users SET game_rank_1 = game_rank_1+1 WHERE id = ?", [$player->user_id]);
                }
                if ($player->rank === 2) {
                    DB::update("UPDATE users SET game_rank_2 = game_rank_2+1 WHERE id = ?", [$player->user_id]);
                }
                if ($player->rank === 3) {
                    DB::update("UPDATE users SET game_rank_3 = game_rank_3+1 WHERE id = ?", [$player->user_id]);
                }
            }
        }
    }
}
