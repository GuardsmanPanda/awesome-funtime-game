<?php

use App\Models\Country;
use Areas\_Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('', [AdminController::class, 'index']);
Route::get('country', function () {return Country::all();});