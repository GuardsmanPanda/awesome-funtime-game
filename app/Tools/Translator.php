<?php

namespace App\Tools;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class Translator
{
    // Word and phrase based translation.
    public static function translate(string $key): string {
       //dd(session()->get('language_code'));
        return config('language.' . (session()->get('language_code') ?? 'en') .'.'. $key, $key);
    }

    public static function setSessionLanguage(User $user): void {
        session()->put('language_code',
            DB::selectOne("SELECT translation_code FROM language WHERE id = ?", [$user->language_id])->translation_code
        );
    }
}
