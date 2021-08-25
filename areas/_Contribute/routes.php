<?php

use App\Tools\Req;
use App\Tools\Auth;
use App\Tools\Resp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Integrations\Streetview\Streetview;
use Areas\_Contribute\TranslationController;

Route::middleware('permission:contribute')->group(function () {
    Route::view('', '_contribute.index');

    Route::prefix('panorama')->middleware('permission:contribute-panorama')->group(function () {
        Route::view('', '_contribute.panorama');
        Route::post('', function () {
            return Streetview::findNearbyPanorama(Req::input('lat'), Req::input('lng'),Req::input('curated') ?? false, 15);
        });
        Route::get('list', function () {
            return Resp::SQLJson("
            SELECT ST_Y(p.panorama_location::geometry) as lat, ST_X(p.panorama_location::geometry) as lng
            FROM panorama p WHERE added_by_user_id IS NOT NULL
        ");
        });
    });

    Route::prefix('translation')->middleware('permission:contribute-translation')->group(function () {
        Route::get('', [TranslationController::class, 'index']);
    });
});

Route::post('/rate/{panorama_id}/{rating}', function (string $panorama_id, int $rating) {
    abort_if(Auth::$user_id === -1, 403, 'Must be logged in to rate images.');
    DB::insert("
        INSERT INTO panorama_rating (panorama_id, user_id, rating) VALUES (?, ?, ?)
        ON CONFLICT (panorama_id, user_id) DO UPDATE SET rating = excluded.rating
    ", [$panorama_id, Auth::$user_id, $rating]);
    return '';
});

