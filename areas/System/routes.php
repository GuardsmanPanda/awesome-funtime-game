<?php

use Areas\System\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('', '/game');
Route::patch('/user/language/{id}', [UserController::class, 'selectLanguage']);


