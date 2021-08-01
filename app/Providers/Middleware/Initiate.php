<?php

namespace App\Providers\Middleware;

use App\Tools\Req;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Initiate {
    public static array $headers = ['Cache-Control' => 'no-store, must-revalidate'];

    public function handle(Request $request, Closure $next) {
        Req::$r = $request;
        $is_write_request = Req::isWriteRequest();
        if ($is_write_request) {
            $idempotency_key = $request->header('idempotency-key') ?? $request->input('_idempotency');
            if ($idempotency_key !== null) {
                DB::insert("INSERT INTO z_idempotency (idempotency_key, user_id, http_verb, http_path) VALUES (?, ?, ?, ?)", [$idempotency_key, session()->get('login_id', -1), $request->getMethod(), $request->path()]);
            }
        }

        $resp = $next($request);
        if (method_exists($resp, 'header')) {
            foreach (self::$headers as $key => $value) {
                $resp->header($key, $value);
            }
        }

        if ($is_write_request && $idempotency_key !== null && $resp->getStatusCode() >= 400) {
            DB::delete("DELETE FROM z_idempotency WHERE idempotency_key = ?", [$idempotency_key]);
        }
        return $resp;
    }
}
