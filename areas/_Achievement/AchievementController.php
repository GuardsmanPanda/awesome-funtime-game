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
}