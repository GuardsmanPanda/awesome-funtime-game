<?php

namespace Areas\Game;

use App\Tools\Auth;
use App\Models\Game;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller {
    public function index(): view {
        return view('game.dashboard.index');
    }

    public function create(Request $r): view {
        if (!Auth::user()->can_create_games) {
            abort(403, 'Not allowed to create games');
        }
        $game = new Game();
        $game->round_count = $r->get('round_count');
        $game->round_time = $r->get('round_time');
        $game->created_by_user_id = Auth::$user_id;
        $game->save();
        return $this->index();
    }
}
