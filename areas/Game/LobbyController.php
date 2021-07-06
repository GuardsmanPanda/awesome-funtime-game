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
        if (Auth::$user_id === -1) {
            Resp::hxRedirectAbort('/login?redirect=' . Req::$r->getRequestUri(), code:401);
        }
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

    public function patchCountrySelection(int $game_id): view {
        $user = Auth::user();
        $user->country_code_1 = Req::input('country_1') ?? $user->country_code_1;
        $user->country_code_2 = Req::input('country_2') ?? $user->country_code_2;
        $user->country_code_3 = Req::input('country_3') ?? $user->country_code_3;
        $user->save();
        return $this->getCountrySelector($game_id);
    }

    public function getCountrySelector(int $game_id): view {
        return view('game.lobby.country-selector', [
            'game_id' => $game_id,
            'user_data' => DB::selectOne("SELECT u.country_code_1, u.country_code_2, u.country_code_3 FROM users u WHERE u.id = ?", [Auth::$user_id]),
            'countries' => DB::select("
                SELECT c.country_code, c.country_name, count(p.panorama_id) as country_count
                FROM country c
                LEFT JOIN panorama p on c.country_code = p.extended_country_code
                LEFT JOIN (
                        SELECT DISTINCT r.panorama_id
                        FROM game_user gu
                        LEFT JOIN round_user ru ON ru.user_id = gu.user_id
                        LEFT JOIN round r ON r.id = ru.round_id
                        WHERE gu.game_id = ?
                        GROUP BY r.id
                ) p3 ON p3.panorama_id = p.panorama_id
                WHERE p3.panorama_id IS NULL
                GROUP BY  c.country_code, c.country_name
                HAVING count(p.panorama_id) > 0
                ORDER BY c.country_name 
         ", [$game_id]),
        ]);
    }
}
