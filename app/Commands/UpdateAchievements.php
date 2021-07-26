<?php

namespace App\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Infrastructure\Achievement\AchievementUtility;

class UpdateAchievements extends Command {
    protected $signature = 'zz:achievements';
    protected $description = 'Update achievements';

    public function handle(): void {
        $this->withProgressBar(User::all(), function ($user) {
            AchievementUtility::updateAllUserAchievements($user);
        });
        AchievementUtility::updateAllAchievementRanks();
        $this->newLine();
    }
}