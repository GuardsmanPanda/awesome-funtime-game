<?php

namespace App\Providers\Middleware;

use Closure;
use App\Tools\Auth;
use Illuminate\Http\Request;

class Permission {
    public function handle(Request $request, Closure $next, string $permission) {
        if (!Auth::has_permission($permission)) {
            abort(403, 'No valid permission for route.');
        }
        return $next($request);
    }
}
