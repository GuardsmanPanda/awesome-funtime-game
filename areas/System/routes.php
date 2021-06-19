<?php

use Areas\System\UserController;
use Illuminate\Support\Facades\Route;
use Areas\System\MapTileStyleController;

Route::redirect('', '/game');
Route::patch('/user/language/{id}', [UserController::class, 'selectLanguage']);

Route::get('/static/tile/{map_style}/{file_name}', [MapTileStyleController::class, 'getMapTile']);


