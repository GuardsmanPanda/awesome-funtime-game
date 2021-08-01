<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PlayerOfGames {
    public const REQUIREMENTS = [0, 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096];
    public const ACHIEVEMENT_ID = 1;

    public static function updateAchievementStatus(User $user): void {
        $au = AchievementUtility::getAchievementUser($user, self::ACHIEVEMENT_ID);
        $au->current_score = self::getScore($user);

        while ($au->current_score >= $au->next_level_score) {
            $au->current_level++;
            $au->next_level_score = self::REQUIREMENTS[$au->current_level + 1];
            AchievementUtility::createAnnouncement($user->id, "Player of games level: " . $au->current_level, self::ACHIEVEMENT_ID);
        }

        $au->save();
    }

    public static function updateAllRanks(): void {
        DB::update("
            UPDATE achievement_user
            SET user_rank = data.rank
            FROM (
                SELECT
                gu.user_id, COUNT(*) as count, rank() OVER( ORDER BY count(*) DESC) as rank
                FROM game_user gu
                GROUP BY gu.user_id
                ORDER BY count DESC
            ) AS data
            WHERE data.user_id = achievement_user.user_id AND achievement_user.achievement_id = ?
        ", [self::ACHIEVEMENT_ID]);
    }

    private static function getScore(User $user): int {
        return DB::selectOne("SELECT COUNT(*) FROM game_user WHERE user_id = ?", [$user->id])->count;
    }
}