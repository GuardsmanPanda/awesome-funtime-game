<?php

namespace Areas\Game;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ResultController {
    public function index(int $id): view {
        return view('game.result.index', [
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
                ", [$id]),
            ]);
    }
}