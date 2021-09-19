<?php

use Illuminate\Support\Facades\Route;
use Areas\_Achievement\AchievementController;

Route::get('', [AchievementController::class, 'index']);
Route::get('accuracy', [AchievementController::class, 'accuracy']);
Route::get('ladder', [AchievementController::class, 'ladder']);