<?php

use Areas\System\UserController;
use Illuminate\Support\Facades\Route;
use Areas\System\MapTileStyleController;

Route::redirect('', '/game');

Route::view('/login', 'login');

Route::patch('/user/map-style/{id}', [UserController::class, 'selectMapStyle']);

Route::get('/static/files/tile/{map_style}/{z}/{x}/{file_name}', [MapTileStyleController::class, 'getMapTile']);


