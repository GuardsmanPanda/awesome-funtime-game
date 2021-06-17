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
            $round->panorama_id = $picker->pickPanorama();
            $round->round_end_at = Carbon::now()->addSeconds($game->round_time);
            $round->save();

            //TODO perf fix
            DB::insert("
                INSERT INTO round_user (round_id, user_id)
                SELECT ?, user_id FROM game_user WHERE game_id = ? 
            ", [$round->id, $game->id]);
           // foreach (DB::select("SELECT user_id FROM game_user WHERE game_id = ?", [$game->id]) as $user) {
           //     DB::insert("INSERT INTO round_user (round_id, user_id) VALUES (?, ?)", [$round->id, $user->user_id]);
           // }

            $game->current_round_id = $round->id;
            $game->is_round_active = true;
            $game->next_round_at = null;
            $game->current_round = $i;
            $game->save();

            sleep($game->round_time - 2);
            $game->is_round_active = false;
            $game->save();

            ScoreCalculator::scoreRound($round);
            $game->next_round_at = Carbon::now()->addSeconds(30);
            $game->save();
            sleep(27);
        }
        $game->ended_at = Carbon::now();
        $game->is_queued = false;
        $game->save();
    }
}
