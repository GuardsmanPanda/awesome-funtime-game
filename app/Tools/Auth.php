<?php

namespace App\Tools;

use App\Models\User;

class Auth {
    public static int $user_id = -1;
    private static User $current_user;

    public static function user(): User|null {
        if (self::$user_id === -1) {
            return null;
        }
        if (!isset(self::$current_user)) {
            self::$current_user = User::find(self::$user_id);
        }
        return self::$current_user;
    }

    public static function is_curator(): bool {
        return self::user() === null ? false : self::$current_user->is_curator;
    }
}
