<?php

use App\Tools\Resp;
use App\Models\Country;
use Areas\_Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Areas\_Admin\StreetviewAdminController;

Route::get('', [AdminController::class, 'index']);
Route::view('country','_admin.country');
Route::patch('country', [AdminController::class, 'patchCountry']);
Route::get('country/list', [AdminController::class, 'listCountry']);

Route::get('country/{country}/language-editor', [AdminController::class, 'getCountryLanguageEditor']);
Route::post('country/{country}/language',   [AdminController::class, 'addLanguageToCountry']);
Route::delete('country/{country}/language/{language_id}',   [AdminController::class, 'deleteLanguageCountry']);

Route::get('country/{country}/fact-editor', [AdminController::class, 'getCountryFactEditor']);
Route::post('country/{country}/fact',   [AdminController::class, 'addFactToCountry']);
Route::patch('country/{country}/fact',   [AdminController::class, 'patchFactCountry']);
Route::get('country/{country_code}/fact/list',   [AdminController::class, 'listFact']);


Route::view('language',  '_admin.language');
Route::post('language',   [AdminController::class, 'createLanguage']);
Route::patch('language', [AdminController::class, 'patchLanguage']);
Route::get('language/list',  [AdminController::class, 'listLanguage']);

Route::prefix('streetview')->group(function () {
    Route::view('', '_admin.streetview');
    Route::post('add', [StreetviewAdminController::class, 'add']);
    Route::get('list', function () {
        return Resp::SQLJson("
            SELECT ST_Y(p.panorama_location::geometry) as lat, ST_X(p.panorama_location::geometry) as lng
            FROM panorama p WHERE added_by_user_id IS NOT NULL
        ");
    });
});
