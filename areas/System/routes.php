<?php

use Areas\System\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('', '/game');
Route::patch('/user/reset-language', [UserController::class, 'resetLanguage']);


