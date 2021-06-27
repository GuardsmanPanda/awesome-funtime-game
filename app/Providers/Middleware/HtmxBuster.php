<?php

namespace App\Providers\Middleware;

use Closure;
use Illuminate\Http\Request;

class HtmxBuster {
    public function handle(Request $request, Closure $next) {
        if ($request->path() === '/') {
            return redirect('/game');
        }
        $a = $request->header('accept');
        if (str_contains($a, 'html') && !$request->header('HX-request') && $request->method() === 'GET') {
            $layout = str_ends_with($request->path(), '/play') ? 'layout-play' : 'layout';
            return response()->view($layout, [
                'primary_hx' => 'hx-get="'. $request->getRequestUri() . '"',
                'area' => explode('/', ltrim($request->path(), '/'))[0],
            ]);
        }
        return $next($request);
    }
}
