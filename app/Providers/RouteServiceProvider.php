<?php

namespace App\Providers;

use Areas\System\AuthenticationController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    public function boot():void {
        $this->routes(function () {
            Route::middleware('web')->group(function() {
                Route::prefix('')->group(base_path('areas/System/routes.php'));
                Route::prefix('stat')->group(base_path('areas/Stat/routes.php'));
                Route::prefix('game')->group(base_path('areas/Game/routes.php'));
                Route::prefix('admin')->group(base_path('areas/_Admin/routes.php'));
                Route::prefix('contribute')->group(base_path('areas/_Contribute/routes.php'));
                Route::prefix('achievement')->group(base_path('areas/_Achievement/routes.php'));

                Route::prefix('dev')->middleware('permission:dev')->group(base_path('areas/_Dev/routes.php'));
            });

            Route::middleware(['cookie', 'session'])->get('auth/twitch-login', [AuthenticationController::class, 'twitchLogin']);
            Route::middleware(['cookie', 'session'])->get('auth/payload-login', [AuthenticationController::class, 'loginWithSignedPayload']);
            Route::middleware(['cookie', 'session'])->get('auth/logout', [AuthenticationController::class, 'logout']);

            Route::middleware('web')->group(function() {
                Route::get('test', function () {
                    abort(500, '22');
                    return 'ok';
                });
            });
        });
    }
}
