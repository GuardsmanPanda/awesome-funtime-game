<?php

namespace Areas\Game;

use App\Models\Game;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ResultController {
    public function index(Game $game): view {
        return view('game.result.index', [
            'game' => $game,

            'rounds' => DB::select("
                SELECT
                    r.id, c.country_name, c.country_code
                FROM round r
                LEFT JOIN panorama p on r.panorama_id = p.panorama_id
                LEFT JOIN country c ON c.country_code = p.extended_country_code
                WHERE game_id = ?
            ", [$game->id]),

            'players' => DB::select("
                SELECT
                    u.display_name, u.country_code, 
                    m.file_name,
                    gu.points_total,
                    RANK() OVER (ORDER BY gu.points_total DESC) AS rank
                FROM game_user gu
                LEFT JOIN users u ON u.id = gu.user_id
                LEFT JOIN marker m ON m.id = u.map_marker_id
                WHERE gu.game_id = ?
                ORDER BY rank
            ", [$game->id]),
            ]);
    }

    public function roundResult(int $game_id, int $round_id): view {
        return view('game.result.round-details', [
            'round' => DB::selectOne("
                SELECT 
                       ST_X(p.panorama_location::geometry) as x, ST_Y(p.panorama_location::geometry) as y,
                       p.jpg_name, p.captured_date
                FROM round r
                LEFT JOIN panorama p on r.panorama_id = p.panorama_id
                WHERE r.id = ?
            ", [$round_id]),

            'players' => DB::select("
                SELECT
                    ST_X(ru.location::geometry) as x, ST_Y(ru.location::geometry) as y,
                    u.display_name, m.file_name
                FROM round_user ru
                LEFT JOIN users u ON u.id = ru.user_id
                LEFT JOIN marker m ON m.id = u.map_marker_id
                WHERE ru.round_id = ?
            ", [$round_id]),
        ]);
    }
}