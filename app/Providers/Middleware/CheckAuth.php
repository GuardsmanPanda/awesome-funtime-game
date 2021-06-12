<?php

namespace App\Providers\Middleware;

use Closure;
use App\Tools\Auth;

class CheckAuth {
    public function handle($request, Closure $next) {
        Auth::$user_id = session()->get('login_id', -1);
        if (!Auth::is_admin() && str_starts_with($request->path(), 'admin')) {
            abort(401, 'Admin area for admins only');
        }
        return $next($request);
    }
}
