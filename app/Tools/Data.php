<?php

namespace App\Tools;

use Illuminate\Support\Facades\DB;

class Data {
    public static function SQLToJson(string $sql, $data = []): string {
        return DB::select("
            SELECT json_agg(t) FROM ($sql) t
        ", $data)[0]->json_agg ?? '[]';
    }
}
