<?php

namespace Areas\Game;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Support\Facades\DB;
use Infrastructure\Game\ScoreCalculator;
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
            $game->next_round_at = Carbon::now()->addSeconds(25);
            $game->save();
            sleep(23);
        }
        $game->ended_at = Carbon::now();
        $game->is_queued = false;
        $game->save();
    }
}
