<?php

use Areas\System\UserController;
use Illuminate\Support\Facades\Route;
use Areas\System\MapTileStyleController;

Route::redirect('', '/game');
Route::patch('/user/language/{id}', [UserController::class, 'selectLanguage']);

Route::get('/static/files/tile/{map_style}/{z}/{file_name}', [MapTileStyleController::class, 'getMapTile']);


