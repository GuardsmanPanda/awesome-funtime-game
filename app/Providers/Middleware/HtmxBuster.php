<?php

namespace App\Providers\Middleware;

use Closure;
use App\Tools\Req;

class HtmxBuster {
    public function handle($request, Closure $next) {
        if ($request->path() === '/') {
            return redirect('/game');
        }
        $a = $request->header('accept');
        if ($a !== 'application/json' && !$request->header('HX-request') && $request->method() === 'GET') {
            $layout = str_ends_with($request->path(), '/play') ? 'layout-play' : 'layout';
            return response()->view($layout, [
                'primary_hx' => 'hx-get="/' .trim($request->path(), '/') . '"',
                'area' => str_starts_with($request->path(), 'admin') ? 'admin' : '',
            ]);
        }
        return $next($request);
    }
}
