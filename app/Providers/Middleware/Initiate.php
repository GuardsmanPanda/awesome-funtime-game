<?php

namespace App\Providers\Middleware;

use App\Tools\Req;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Initiate {
    public static array $headers = ['Cache-Control' => 'no-store, must-revalidate'];

    public function handle(Request $request, Closure $next) {
        Req::$r = $request;
        $resp = $next($request);
        if (!$resp instanceof  JsonResponse) {
            foreach (self::$headers as $key => $value) {
                $resp->header($key, $value);
            }
        }
        return $resp;
    }
}
