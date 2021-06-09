<?php

use App\Models\Country;
use Areas\_Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('', [AdminController::class, 'index']);
Route::view('country','_admin.country');
Route::patch('country', [AdminController::class, 'listCountry']);
Route::get('country/list', [AdminController::class, 'listCountry']);

Route::view('language',  '_admin.language');
Route::get('language/list',  [AdminController::class, 'listLanguage']);

