<?php

namespace App\Providers\Middleware;

use App\Tools\Auth;
use App\Tools\Req;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Idempotency {

    public function handle(Request $request, Closure $next) {
        $is_write_request = Req::isWriteRequest();

        if ($is_write_request) {
            $idempotency_key = $request->header('idempotency-key') ?? $request->input('_idempotency');
            if ($idempotency_key !== null) {
                DB::insert("INSERT INTO z_idempotency (idempotency_key, user_id, http_verb, http_path) VALUES (?, ?, ?, ?)", [$idempotency_key, Auth::$user_id, $request->getMethod(), $request->path()]);
            }
        }

        $resp = $next($request);

        //REMOVE IDEMPOTENCY ON SERVER ERROR
        if ($is_write_request && $idempotency_key !== null && $resp->getStatusCode() >= 400) {
            DB::delete("DELETE FROM z_idempotency WHERE idempotency_key = ?", [$idempotency_key]);
        }
        return $resp;
    }
}