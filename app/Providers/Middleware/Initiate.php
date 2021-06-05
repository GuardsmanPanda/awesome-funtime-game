<?php

namespace App\Providers\Middleware;

use App\Tools\Req;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Initiate {
    public static array $headers = [];

    public function handle(Request $request, Closure $next) {
        Req::$r = $request;
        $resp = $next($request);
        foreach (self::$headers as $key => $value) {
            $resp->header($key, $value);
        }
        return $resp;
    }
}
