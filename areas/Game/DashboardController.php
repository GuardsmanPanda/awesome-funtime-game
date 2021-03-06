<?php

namespace Areas\Game;

use App\Tools\Auth;
use App\Tools\Resp;
use App\Models\Game;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller {
    public function index(): view {
        return view('game.dashboard.index', [
            'players' => DB::select("
                SELECT 
                    u.display_name, u.country_code,
                    RANK() OVER (ORDER BY ru.elo_rating DESC),
                    ru.elo_rating,
                    m.file_name
                FROM users u
                LEFT JOIN realm_user ru on ru.user_id = u.id
                LEFT JOIN marker m ON u.map_marker_id = m.id
                WHERE  ru.realm_id = ? AND u.last_login_at > now() - INTERVAL '1 month'
                ORDER BY ru.elo_rating DESC 
                LIMIT 10
            ", [Auth::user()?->logged_into_realm_id ?? 1]),
        ]);
    }

    public function create(Request $r): view {
        if (!Auth::user()->can_create_games) {
            abort(403, 'Not allowed to create games');
        }
        $game = new Game();
        $game->round_count = $r->get('round_count');
        $game->round_time = $r->get('round_time');
        $game->realm_id = $r->get('realm_id');
        $game->created_by_user_id = Auth::$user_id;
        $game->save();

        return $this->index();
    }

    public function active(): JsonResponse {
        return Resp::SQLJson("
        SELECT g.id, g.round_count, g.round_time, g.current_round, u.display_name,
               (SELECT COUNT(*) FROM game_user WHERE game_id = g.id) as player_count
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.round_count != g.current_round AND g.realm_id = ?
        ORDER BY u.display_name", [Auth::user()?->logged_into_realm_id ?? 1]);
    }

    public function recent(): JsonResponse {
        return Resp::SQLJson("
        SELECT g.id, g.round_count, u.display_name, g.ended_at,
               (SELECT COUNT(*) FROM game_user WHERE game_id = g.id) as player_count
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.ended_at IS NOT NULL AND g.realm_id = ?
        ORDER BY g.ended_at DESC LIMIT 6", [Auth::user()?->logged_into_realm_id ?? 1]);
    }
}
