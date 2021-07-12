<?php

namespace App\Tools;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class Auth {
    public static int $user_id = -1;
    private static User $current_user;
    private static array|null $permissions = null;

    public static function user(): User|null {
        if (self::$user_id === -1) {
            return null;
        }
        if (!isset(self::$current_user)) {
            self::$current_user = User::find(self::$user_id);
        }
        return self::$current_user;
    }

    public static function has_permission(string $permission_slug): bool {
        if (self::$permissions === null) {
            self::$permissions = [];
            $per = DB::select("
                SELECT p.permission_slug
                FROM permission_user pu 
                LEFT JOIN permission p ON p.id = pu.permission_id
                WHERE pu.user_id = ?", [self::$user_id]);
            foreach ($per as $p) {
                self::$permissions[] = $p->permission_slug;
            }
        }
        return in_array($permission_slug, self::$permissions, true);
    }

    public static function is_admin(): bool {
        return self::user() === null ? false : self::$current_user->is_admin;
    }
}
