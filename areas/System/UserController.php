<?php

namespace Areas\System;

use App\Tools\Auth;
use Illuminate\Routing\Controller;
use Infrastructure\Language\LFanguageUtility;

class UserController extends Controller {
    public function resetLanguage() {
        Auth::user()->language_code = LanguageUtility::getAcceptedLanguage();
        Auth::user()->save();
    }
}