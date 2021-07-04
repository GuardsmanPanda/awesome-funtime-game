<?php

namespace Areas\Stat;

use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class StatController extends Controller {
    public function index(): view  {
        return view('stat.index', [
            'info' => DB::selectOne("
                SELECT
                    (SELECT COUNT(*) FROM users) as user_count,
                    (SELECT COUNT(*) FROM game) as game_count,
                    (SELECT COUNT(*) FROM round) as round_count,
                    (SELECT COUNT(*) FROM round_user) as guess_count,
                    (SELECT COUNT(*) FROM panorama) as panorama_count
            "),
        ]);
    }
}