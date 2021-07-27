<?php

namespace Areas\_Achievement;

use App\Tools\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Infrastructure\Achievement\AchievementUtility;

class AchievementController extends Controller {
    public function index(): view {
        $user = Auth::user();
        if ($user->achievement_refresh_needed) {
            AchievementUtility::updateAllUserAchievements($user);
            $user->achievement_refresh_needed = false;
            $user->save();
        }
        return view('_achievement.index', [
            'achievements' => DB::select("
                SELECT
                    a.achievement_name, a.achievement_description,
                    au.current_level, au.current_score, au.next_level_score, au.user_rank
                FROM achievement_user au
                LEFT JOIN achievement a on au.achievement_id = a.id
                WHERE au.user_id = ? ORDER BY a.achievement_name
            ", [$user->id]),
        ]);
    }
}