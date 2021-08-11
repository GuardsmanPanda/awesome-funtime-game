<?php

namespace Areas\_Achievement;

use App\Tools\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Infrastructure\Achievement\CountryUnlock;
use Infrastructure\Achievement\AchievementUtility;

class AchievementController extends Controller {
    public function index(): view {
        $user = Auth::user();
        if ($user->achievement_refresh_needed) {
            AchievementUtility::updateAllUserAchievements($user);
        }
        $stats = CountryUnlock::getUserStat($user->id);
        return view('_achievement.index', [
            'user' =>$user,
            'achievements' => DB::select("
                SELECT
                    a.achievement_name, a.achievement_description, a.achievement_type,
                    au.current_level, au.current_score, au.next_level_score, au.user_rank
                FROM achievement_user au
                LEFT JOIN achievement a on au.achievement_id = a.id
                WHERE au.user_id = ?
                ORDER BY a.sort_order
            ", [$user->id]),
            'countries' => $stats['countries'],
            'country_count' => $stats['count'],
        ]);
    }

    public static function accuracy(): view {
        return view('_achievement.accuracy', [
            'graph_data' => DB::selectOne("
                SELECT json_agg(x.row) as row, json_agg(x.dist) as distance, json_agg(x.cc) as cc
                FROM (
                    SELECT
                        ROW_NUMBER() over () as row,
                        ROUND(AVG(ru.distance/1000) OVER (ROWS 500 PRECEDING)) as dist,
                        ROUND(AVG(CASE WHEN ru.extended_country_code = p.extended_country_code THEN 100 ELSE 0 END) OVER (ROWS 500 PRECEDING), 2) as cc
                    FROM round_user ru
                    LEFT JOIN round r on ru.round_id = r.id
                    LEFT JOIN panorama p on r.panorama_id = p.panorama_id
                    WHERE user_id = ?
                ) x WHERE x.row >= 20  AND x.row % ceil((SELECT count(*) FROM round_user r2 WHERE r2.user_id = ?)/400)::integer = 0
            ", [Auth::$user_id, Auth::$user_id]),
        ]);
    }
}