<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use App\Models\AchievementUser;
use Illuminate\Support\Facades\DB;
use App\Models\AchievementNotification;

class AchievementUtility {
    public static function updateAllUserAchievements(User $user): void {
        PlayerOfGames::updateAchievementStatus($user);
        CountryUnlock::updateAchievementStatus($user);
        CountryExpert::updateAchievementStatus($user);
        Reviewer::updateAchievementStatus($user);
        Rounder::updateAchievementStatus($user);

        $contrib = DB::selectOne("
            SELECT p.panorama_id FROM panorama p WHERE p.added_by_user_id  = ? LIMIT 1 
        ", [$user->id]);
        if ($contrib !== null) {
            ContributePanorama::updateAchievementStatus($user);
            ContributeRounds::updateAchievementStatus($user);
            ContributeWow::updateAchievementStatus($user);
            ContributeGreat::updateAchievementStatus($user);
            ContributeGood::updateAchievementStatus($user);
        }

        $user->game_rank_rank = DB::selectOne("
            SELECT COUNT(*)+1 as count FROM users 
            WHERE 
                game_rank_1 > ?
                OR (game_rank_1 = ? AND game_rank_2 > ?)
                OR (game_rank_1 = ? AND game_rank_2 = ? AND game_rank_3 > ?)
        ", [$user->game_rank_1, $user->game_rank_1, $user->game_rank_2, $user->game_rank_1, $user->game_rank_2, $user->game_rank_3])->count;

        $user->achievement_refresh_needed = false;
        $user->save();
    }

    public static function updateAllAchievementRanks(): void {
        PlayerOfGames::updateAllRanks();
        CountryExpert::updateAllRanks();
        Reviewer::updateAllRanks();
        Rounder::updateAllRanks();

        ContributePanorama::updateAllRanks();
        ContributeRounds::updateAllRanks();
        ContributeWow::updateAllRanks();
        ContributeGreat::updateAllRanks();
        ContributeGood::updateAllRanks();
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

    public static function createAnnouncement(int $user_id, string $message, int $achievement_id): void {
        $an = new AchievementNotification();
        $an->achievement_id = $achievement_id;
        $an->notification_message = $message;
        $an->user_id = $user_id;
        $an->save();
    }
}