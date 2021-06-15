<?php

use App\Tools\Resp;
use Areas\Game\PlayController;
use Areas\Game\LobbyController;
use Areas\Game\ResultController;
use Areas\Game\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('', [DashboardController::class, 'index']);
Route::get('active', function () { return Resp::SQLJson("
        SELECT g.id, g.round_count, g.round_time, g.current_round, u.display_name
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.round_count != g.current_round
        ORDER BY u.display_name");
});
Route::get('recent', function () { return Resp::SQLJson("
        SELECT g.id, g.round_count, u.display_name, g.ended_at
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.ended_at IS NOT NULL
        ORDER BY g.ended_at DESC LIMIT 6");
});

Route::post('create', [DashboardController::class, 'create']);

Route::post('{game}/start', [LobbyController::class, 'start']);
Route::get('{game}/lobby', [LobbyController::class, 'index']);
Route::get('{game}/lobby-status', [LobbyController::class, 'lobbyStatus']);

Route::get('{id}/play', [PlayController::class, 'index']);
Route::post('{game}/guess', [PlayController::class, 'guess']);

Route::get('{game}/result', [ResultController::class, 'index']);


Route::post('marker/{marker}', [LobbyController::class, 'changeMapMarker']);
Route::get("{game}/player", function ($game) {
    return Resp::SQLJson("
            SELECT u.country_code, u.display_name, m.file_name
            FROM game_user gu
            LEFT JOIN users u ON u.id = gu.user_id
            LEFT JOIN marker m ON m.id = u.map_marker_id
            WHERE gu.game_id = ?
            ORDER BY u.display_name
    ", [$game]);
});
