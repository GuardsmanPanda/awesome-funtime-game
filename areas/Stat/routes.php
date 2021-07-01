<?php

use App\Tools\Resp;
use Illuminate\Support\Facades\Route;

Route::view('', 'stat.index');
Route::view('country', 'stat.country');
Route::get('/country/list', function () { return Resp::SQLJson("
        SELECT 
            c.country_code, c.country_name,
            (SELECT COUNT(*) FROM panorama p WHERE p.extended_country_code = c.country_code) as panorama_count
        FROM country c
    ");
});

