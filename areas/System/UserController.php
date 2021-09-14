<?php

namespace Areas\System;

use App\Tools\Auth;
use App\Tools\Resp;
use App\Tools\Translator;
use Illuminate\Routing\Controller;
use Infrastructure\Language\LanguageUtility;

class UserController extends Controller {
    public function selectMapStyle(int $id): void {
        Auth::user()->map_style_id = $id;
        Auth::user()->save();
        Resp::hxRefresh('Map style chosen');
    }
}