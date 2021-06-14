<?php

namespace Areas\System;

use App\Tools\Auth;
use App\Tools\Resp;
use Illuminate\Routing\Controller;
use Infrastructure\Language\LanguageUtility;

class UserController extends Controller {
    public function resetLanguage(): void {
        Auth::user()->translation_code = LanguageUtility::getAcceptedLanguage();
        Auth::user()->save();
        Resp::hxRedirectAbort('/game');
    }
}