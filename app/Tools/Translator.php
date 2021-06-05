<?php

namespace App\Tools;

class Translator
{
    // Word and phrase based translation.
    public static function translate(string $key): string {
        return config('language.' . Auth::user()?->language_code ?? 'en' .'.'. $key, $key);
    }

    public static function getUserLangCCIso(): string {
        return match (Auth::user()->language) {
            'da' => 'DK',
            'de' => 'DE',
            'en' => 'GB',
            'fi' => 'FI',
            'no' => 'NO',
            'sv' => 'SE',
            default => 'XX'
        };
    }
}
