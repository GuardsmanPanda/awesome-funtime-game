<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\AchievementNotification;

class Reviewer {
    public const REQUIREMENTS = [0, 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048];
    public const ACHIEVEMENT_ID = 3;

    public static function updateAchievementStatus(User $user): void {
        $au = AchievementUtility::getAchievementUser($user, self::ACHIEVEMENT_ID);
        $au->current_score = self::getScore($user);

        while ($au->current_score >= $au->next_level_score) {
            $au->current_level++;
            $au->next_level_score = self::REQUIREMENTS[$au->current_level + 1];
            $an = new AchievementNotification();
            $an->achievement_id = self::ACHIEVEMENT_ID;
            $an->user_id = $user->id;
            $an->notification_message = "Reviewer level: " . $au->current_level;
            $an->save();
        }

        $au->save();
    }

    public static function updateAllRanks(): void {
        DB::update("
            UPDATE achievement_user
            SET user_rank = data.rank
            FROM (
                SELECT
                pr.user_id, COUNT(*) as count, rank() OVER( ORDER BY count(*) DESC) as rank
                FROM panorama_rating pr
                GROUP BY pr.user_id
                ORDER BY count DESC
            ) AS data
            WHERE data.user_id = achievement_user.user_id AND achievement_user.achievement_id = ?
        ", [self::ACHIEVEMENT_ID]);
    }

    private static function getScore(User $user): int {
        return DB::selectOne("SELECT COUNT(*) FROM panorama_rating WHERE user_id = ?", [$user->id])->count;
    }
}