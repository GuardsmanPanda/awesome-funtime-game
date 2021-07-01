<?php

use App\Tools\Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::view('', '_dev.index');
Route::view('download', '_dev.download');
Route::view('finder', '_dev.finder');
Route::post('finder/find', function () {
    return view('_dev.find', ['data' => DB::select("
            SELECT p.extended_country_code, p.region_name, p.state_name, p.state_district_name, 
                   p.city_name, p.county_name,
                   round((st_distance(p.panorama_location, ?)/1000)::numeric,0) as distance
            FROM panorama p 
            ORDER BY st_distance(p.panorama_location, ?)        
            LIMIT 500
        ", [ 'POINT(' . Req::input('lng') . ' ' . Req::input('lat') . ')', 'POINT(' . Req::input('lng') . ' ' . Req::input('lat') . ')'])]);
});