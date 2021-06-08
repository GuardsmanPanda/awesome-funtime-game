<?php

namespace App\Providers;

use App\Models\Game;
use Integrations\Streetview\Streetview;
use Infrastructure\Game\PanoramaPicker;
use Areas\System\AuthenticationController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    public function boot():void {
        $this->routes(function () {
            Route::middleware('web')->group(function() {
                Route::prefix('')->group(base_path('areas/System/routes.php'));
                Route::prefix('game')->group(base_path('areas/Game/routes.php'));
                Route::prefix('admin')->group(base_path('areas/_Admin/routes.php'));
            });

            Route::middleware(['cookie', 'session'])
                ->get('auth/twitch-login', [AuthenticationController::class, 'twitchLogin']);

            Route::get('/test', function () {
                $pick = new PanoramaPicker(Game::find(3));
                return $pick->pickPanorama();
            });
        });
    }
}
