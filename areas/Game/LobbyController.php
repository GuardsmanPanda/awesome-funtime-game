<?php

namespace Areas\Game;

use Throwable;
use App\Tools\Req;
use Carbon\Carbon;
use App\Tools\Auth;
use App\Tools\Resp;
use App\Models\Game;
use App\Models\Marker;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class LobbyController extends Controller {
    public function index(Game $game): view|RedirectResponse|string {
        if ($game->current_round > 0 && !$game->is_queued) {
            Resp::hxRedirectAbort('/game/' . $game->id . '/result');
        }
        if ($game->current_round !== 0) {
            Resp::hxRedirectAbort('/game/' . $game->id . '/play');
        }

        if (Auth::user()) {
            DB::insert("
            INSERT INTO game_user (game_id, user_id) VALUES (?, ?)
            ON CONFLICT (game_id, user_id) DO NOTHING
        ", [$game->id, Auth::$user_id]);
        }
        return view('game.lobby.index', [
            'game' => DB::selectOne("
                SELECT g.*, u.display_name
                FROM game g
                LEFT JOIN users u ON u.id = g.created_by_user_id
                WHERE g.id = ?
            ", [$game->id]),
        ]);
    }

    public function changeMapMarker(Marker $marker): view {
        Auth::user()->map_marker_id = $marker->id;
        Auth::user()->save();
        return view('game.lobby.map-marker');
    }

    public function lobbyStatus(Game $game): view  {
        if ($game->current_round > 0) {
            Resp::hxRedirectAbort('/game/' .$game->id . '/play');
        }
        return view('game.lobby.lobby-status', [
            'game' => $game,
            'to_start' => max($game->game_start_at !== null ? (int)Carbon::now()->diffInSeconds($game->game_start_at, false) : 0, 2),
        ]);
    }

    public function start(Game $game): string  {
        try {
            DB::beginTransaction();
            RunGameJob::dispatch($game->id, Req::input('countdown'));
            $game->is_queued = true;
            $game->save();
            DB::commit();
            return 'Queued';
        } catch (Throwable $t) {
            DB::rollBack();
            report($t);
        }
        return 'Error';
    }
}
