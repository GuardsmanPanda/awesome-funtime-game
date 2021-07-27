<?php

namespace Infrastructure\Achievement;

use App\Models\User;
use App\Models\AchievementUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

class CountryUnlock {
    public const ACHIEVEMENT_ID = 5;

    public static function updateAchievementStatus(User $user): void {
        $au = AchievementUser::where('achievement_id', '=', self::ACHIEVEMENT_ID)
            ->where('user_id', '=', $user->id)->first();
        if ($au === null) {
            $au = new AchievementUser();
            $au->achievement_data = new ArrayObject();
            $au->achievement_id = self::ACHIEVEMENT_ID;
            $au->user_id = $user->id;
            $au->current_score = 0;
            $au->current_level = 0;
        }
        $au->next_level_score = 240;

        $country = DB::select("
            SELECT
                p.extended_country_code, COUNT(*)
            FROM round_user ru
                     LEFT JOIN round r on ru.round_id = r.id
                     LEFT JOIN panorama p on r.panorama_id = p.panorama_id
               WHERE ru.extended_country_code = p.extended_country_code AND ru.user_id = ?
            GROUP BY ru.user_id, p.extended_country_code        
        ", [$user->id]);

        foreach ($country as $c) {
            $au->achievement_data[$c->extended_country_code] = $c->count;
        }
        $au->current_score = count($country);
        $au->save();
    }
}