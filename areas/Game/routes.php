<?php

use App\Tools\Auth;
use App\Tools\Resp;
use Areas\Game\PlayController;
use Areas\Game\LobbyController;
use Areas\Game\ResultController;
use Areas\Game\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('', [DashboardController::class, 'index']);
Route::get('active', function () { return Resp::SQLJson("
        SELECT g.id, g.round_count, g.round_time, g.current_round, u.display_name,
               (SELECT COUNT(*) FROM game_user WHERE game_id = g.id) as player_count
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.round_count != g.current_round AND g.realm_id = ?
        ORDER BY u.display_name", [Auth::user()->logged_into_realm_id]);
});
Route::get('recent', function () {
    return Resp::SQLJson("
        SELECT g.id, g.round_count, u.display_name, g.ended_at,
               (SELECT COUNT(*) FROM game_user WHERE game_id = g.id) as player_count
        FROM game g
        LEFT JOIN users u ON u.id = g.created_by_user_id
        WHERE g.ended_at IS NOT NULL AND g.realm_id = ?
        ORDER BY g.ended_at DESC LIMIT 6", [Auth::user()->logged_into_realm_id]);
});

Route::view('create/form', 'game.dashboard.create-game-form');
Route::post('create', [DashboardController::class, 'create']);


Route::post('{game}/leave', [LobbyController::class, 'leave']);
Route::delete('{game}', [LobbyController::class, 'delete']);
Route::post('{game}/start', [LobbyController::class, 'start']);
Route::get('{game}/lobby', [LobbyController::class, 'index']);
Route::get('{game_id}/lobby-status', [LobbyController::class, 'lobbyStatus']);
Route::view('lobby/map-selector', 'game.lobby.map-selector');
Route::get('lobby/country-selector/{game_id}', [LobbyController::class, 'getCountrySelector']);
Route::patch('lobby/country-selector/{game_id}', [LobbyController::class, 'patchCountrySelection']);

Route::get('{id}/play', [PlayController::class, 'index']);
Route::post('{game}/guess', [PlayController::class, 'guess']);

Route::get('{game}/result', [ResultController::class, 'index']);
Route::get('{game_id}/result/round/{round_id}', [ResultController::class, 'roundResult']);


Route::post('marker/{marker}', [LobbyController::class, 'changeMapMarker']);
Route::get("{game_id}/player", function ($game_id) {
    return Resp::SQLJson("
            SELECT u.country_code, u.display_name, m.file_name, c.country_name
            FROM game_user gu
            LEFT JOIN users u ON u.id = gu.user_id
            LEFT JOIN marker m ON m.id = u.map_marker_id
            LEFT JOIN country c ON c.country_code = u.country_code
            WHERE gu.game_id = ?
            ORDER BY u.display_name
    ", [$game_id]);
});
