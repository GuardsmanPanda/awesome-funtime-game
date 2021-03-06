<?php

use App\Tools\Resp;
use Areas\Stat\StatController;
use Illuminate\Support\Facades\Route;

Route::get('', [StatController::class, 'index']);
Route::get('user-panorama', [StatController::class, 'getUserPanoramaStats']);

Route::view('country', 'stat.country');
Route::get('/country/list', function () { return Resp::SQLJson("
        SELECT 
            c.country_code, c.country_name,
            (SELECT COUNT(*) FROM panorama p WHERE p.extended_country_code = c.country_code) as panorama_count,
            (SELECT COUNT(*) FROM panorama p WHERE p.extended_country_code = c.country_code AND p.added_by_user_id IS NOT NULL) as curated_count,
            (SELECT COUNT(*) FROM panorama p
             WHERE 
                   p.extended_country_code = c.country_code AND p.added_by_user_id IS NOT NULL
                    AND NOT EXISTS(SELECT * FROM round r WHERE r.panorama_id = p.panorama_id)
                   ) as remaining_count,
            c.panorama_buffer - (SELECT COUNT(*) FROM panorama p
                WHERE 
                    p.extended_country_code = c.country_code AND p.added_by_user_id IS NOT NULL
                    AND NOT EXISTS(SELECT * FROM round r WHERE r.panorama_id = p.panorama_id)
                    ) as missing_count
        FROM country c
        WHERE c.country_code != 'GB'
    ");
});

