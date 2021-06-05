<?php

namespace App\Providers\Middleware;

use Closure;
use App\Tools\Auth;

class CheckAuth {
    public function handle($request, Closure $next) {
        Auth::$user_id = session()->get('login_id', -1);
        return $next($request);
    }
}
