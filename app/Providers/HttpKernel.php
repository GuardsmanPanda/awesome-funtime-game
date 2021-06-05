<?php

namespace App\Providers;

use App\Providers\Middleware\Initiate;
use App\Providers\Middleware\CheckAuth;
use App\Providers\Middleware\HtmxBuster;
use App\Providers\Middleware\TrimStrings;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;

class HttpKernel extends Kernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        Initiate::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            CheckAuth::class,
            HtmxBuster::class,
            SubstituteBindings::class,
        ],

        'api' => [
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'cookie' => AddQueuedCookiesToResponse::class,
        'htmx' => HtmxBuster::class,
        'session' => StartSession::class,
    ];
}
