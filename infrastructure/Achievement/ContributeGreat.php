<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ContributeGreat {
    public const REQUIREMENTS = [0, 1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096];
    public const ACHIEVEMENT_ID = 9;

    public static function updateAchievementStatus(User $user): void {
        $au = AchievementUtility::getAchievementUser($user, self::ACHIEVEMENT_ID);
        $au->current_score = self::getScore($user);

        while ($au->current_score >= $au->next_level_score) {
            $au->current_level++;
            $au->next_level_score = self::REQUIREMENTS[$au->current_level + 1];
            AchievementUtility::createAnnouncement($user->id, "Contributed panoramas voted Great level: " . $au->current_level, self::ACHIEVEMENT_ID);
        }
        $au->save();
    }

    public static function updateAllRanks(): void {
        DB::update("
            UPDATE achievement_user
            SET user_rank = data.rank
            FROM (
                SELECT
                p.added_by_user_id, COUNT(*) as count, rank() OVER( ORDER BY count(*) DESC) as rank
                FROM panorama_rating pr
                LEFT JOIN panorama p on pr.panorama_id = p.panorama_id
                WHERE p.added_by_user_id IS NOT NULL AND pr.rating = 6
                GROUP BY p.added_by_user_id
                ORDER BY count DESC
            ) AS data
            WHERE data.added_by_user_id = achievement_user.user_id AND achievement_user.achievement_id = ?
        ", [self::ACHIEVEMENT_ID]);
    }

    private static function getScore(User $user): int {
        return DB::selectOne("
            SELECT COUNT(*) FROM panorama_rating pr
            LEFT JOIN panorama p on pr.panorama_id = p.panorama_id
            WHERE p.added_by_user_id = ? AND pr.rating = 6
        ", [$user->id])->count;
    }
}