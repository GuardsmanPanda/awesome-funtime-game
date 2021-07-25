<?php

use Illuminate\Support\Facades\Route;
use Areas\_Achievement\AchievementController;

Route::get('', [AchievementController::class, 'index']);