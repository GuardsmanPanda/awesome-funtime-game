<?php

namespace Areas\System;

use App\Tools\Auth;
use App\Tools\Resp;
use App\Tools\Translator;
use Illuminate\Routing\Controller;
use Infrastructure\Language\LanguageUtility;

class UserController extends Controller {
    public function selectLanguage(string $id): void {
        Auth::user()->language_id = $id === 'reset' ? LanguageUtility::getAcceptedLanguage() : (int)$id;
        Auth::user()->save();
        Translator::setSessionLanguage(Auth::user());
        Resp::hxRefresh('Language Chosen');
    }
}