<?php

namespace App\Tools;

use Illuminate\Http\Request;

class Req {
    public static Request $r;

    public static function header(string $name): string|array|null {
        return self::$r->header($name);
    }

    public static function input(string $name): mixed {
        return self::$r->input($name);
    }

    public static function content(): string {
        return self::$r->getContent();
    }
}
