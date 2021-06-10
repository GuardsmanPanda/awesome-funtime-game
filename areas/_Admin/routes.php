<?php

use App\Models\Country;
use Areas\_Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('', [AdminController::class, 'index']);
Route::view('country','_admin.country');
Route::patch('country', [AdminController::class, 'patchCountry']);
Route::get('country/list', [AdminController::class, 'listCountry']);

Route::get('country/{country}/language-editor', [AdminController::class, 'getCountryLanguageEditor']);


Route::view('language',  '_admin.language');
Route::post('language',   [AdminController::class, 'createLanguage']);
Route::get('language/list',  [AdminController::class, 'listLanguage']);

