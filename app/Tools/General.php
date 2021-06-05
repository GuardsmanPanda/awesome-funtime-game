<?php

namespace App\Tools;

use Ausi\SlugGenerator\SlugGenerator;

class General
{
    public static function stringToSlug(string $text): string {
        $generator = new SlugGenerator(['ignoreChars' => "'"]);
        return $generator->generate($text);
    }

    public static function jwtToArray(string $jwt): array {
        return json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))), true, 512, JSON_THROW_ON_ERROR);
    }
}
