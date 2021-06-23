<?php

namespace Areas\Game;

use Carbon\Carbon;
use App\Tools\Auth;
use App\Tools\Resp;
use App\Models\Game;
use App\Models\Marker;
use App\Models\Country;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PlayController {
    public function index(int $id): view {
        $game = DB::selectOne("
                SELECT
                    g.id, g.next_round_at, g.current_round_id, g.round_count, g.is_queued, g.current_round,
                    r.round_end_at,
                    p.file_name, p.country_code, ST_X(p.panorama_location::geometry) as x, ST_Y(p.panorama_location::geometry) as y,
                    p.city_name, p.state_name, p.country_name, p.captured_date,
                    cf.fact_text
                FROM game g
                LEFT JOIN round r ON r.id = g.current_round_id
                LEFT JOIN panorama p on r.panorama_id = p.panorama_id
                LEFT JOIN country_fact cf ON cf.id = r.country_fact_id
                WHERE g.id = ?
            ", [$id]);

        if (!$game->is_queued) {
            Resp::hxRedirectAbort('/game/'.$game->id.'/result', 'Game is done');
        }

        $round_diff = Carbon::now()->diffInSeconds(Carbon::parse($game->round_end_at), false);

        if ($round_diff < 2) {
            if ($game->next_round_at === null) {
                return view('game.play.round-result-pending', [
                    'countdown_seconds' => 3,
                    'game' => $game,
                ]);
            }

            return view('game.play.round-result', [
                'game' => $game,
                'countdown_seconds' => max(Carbon::now()->diffInSeconds(Carbon::parse($game->next_round_at), false), 3),
                'country' => Country::find($game->country_code),
                'languages' => DB::select("
                    SELECT l.language_name, cl.percentage FROM country_language cl
                    LEFT JOIN language l on cl.language_id = l.id
                    WHERE cl.country_code = ? ORDER BY percentage
                ", [$game->country_code]),
                'players' => DB::select("
                    SELECT
                        u.display_name, u.country_code,
                        m.file_name,
                        ru.points, ru.is_correct_country, ru.distance,
                        RANK() OVER (ORDER BY ru.points DESC) AS rank,
                        ST_X(ru.location::geometry) as x, ST_Y(ru.location::geometry) as y
                    FROM round_user ru
                    LEFT JOIN users u ON u.id = ru.user_id
                    LEFT JOIN marker m ON m.id = u.map_marker_id
                    WHERE ru.round_id = ?
                    ORDER BY rank
                ", [$game->current_round_id]),
            ]);
        }

        return view('game.play.panorama', [
            'game' => $game,
            'out' => DB::select("
                SELECT c.country_code, c.country_name
                FROM game g
                RIGHT JOIN round r ON r.game_id = g.id AND r.id != g.current_round_id
                LEFT JOIN panorama p ON p.panorama_id = r.panorama_id
                LEFT JOIN country c ON c.country_code = p.country_code
                WHERE g.id = ? ORDER BY r.id
        ", [$id]),
            'countdown_seconds' => $round_diff, //TODO: use proper local time
            'marker' => Marker::find(Auth::user()->map_marker_id)->file_name,
        ]);
    }

    public function guess(Request $r, Game $game): \Illuminate\Http\Response {
        if (!$game->is_round_active) {
            abort(428, 'Round is over');
        }
        $lng = $r->input('lng');
        $lat = $r->input('lat');
        DB::insert("
            INSERT INTO round_user (round_id, user_id, location) VALUES (?, ?, ST_GeographyFromText(?))
            ON CONFLICT (round_id, user_id) DO UPDATE SET location = EXCLUDED.location
        ", [$game->current_round_id, Auth::$user_id, "POINT($lng $lat)"]);
        return Response::noContent(202);
    }
}
