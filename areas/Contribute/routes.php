<?php

use App\Tools\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/rate/{panorama_id}/{rating}', function (string $panorama_id, int $rating) {
    $test = DB::selectOne("
        SELECT p.panorama_id FROM panorama p
        LEFT JOIN round r ON r.panorama_id = p.panorama_id
        LEFT JOIN round_user ru on r.id = ru.round_id
        WHERE p.panorama_id = ? AND ru.user_id = ?", [$panorama_id, Auth::$user_id]);
    if ($test === null || $rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Not allowed to rate this panorama");
    }
    DB::insert("
        INSERT INTO panorama_rating (panorama_id, user_id, rating) VALUES (?, ?, ?)
        ON CONFLICT (panorama_id, user_id) DO UPDATE SET rating = excluded.rating
    ", [$panorama_id, Auth::$user_id, $rating]);
    return '';
});

