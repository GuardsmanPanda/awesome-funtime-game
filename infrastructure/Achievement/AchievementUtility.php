<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use App\Models\AchievementUser;

class AchievementUtility {
    public static function updateAllUserAchievements(User $user): void {
        PlayerOfGames::updateAchievementStatus($user);
        Reviewer::updateAchievementStatus($user);
        Rounder::updateAchievementStatus($user);
        $user->achievement_refresh_needed = false;
    }

    public static function updateAllAchievementRanks(): void {
        PlayerOfGames::updateAllRanks();
        Reviewer::updateAllRanks();
        Rounder::updateAllRanks();
    }

    public static function getAchievementUser(User $user, int $achievement_id): AchievementUser {
        $res = AchievementUser::where('achievement_id', '=', $achievement_id)
            ->where('user_id', '=', $user->id)->get();
        if (count($res) === 1) {
            return $res[0];
        }
        $au = new AchievementUser();
        $au->achievement_id = $achievement_id;
        $au->user_id = $user->id;
        $au->current_score = 0;
        $au->current_level = 0;
        $au->next_level_score = 1;
        return $au;
    }
}