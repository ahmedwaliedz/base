<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load versioned API routes dynamically
            Route::prefix('api')->middleware(['api.lang'])->group(function () {
                // Helper function to load routes from version folder
                $loadVersionRoutes = function ($version) {
                    $routesPath = base_path("routes/api/{$version}");
                    if (is_dir($routesPath)) {
                        $routeFiles = glob($routesPath . '/*.php');
                        foreach ($routeFiles as $routeFile) {
                            Route::group([], $routeFile);
                        }
                    }
                };
                
                // Dynamically discover API versions from folder names
                $apiRoutesPath = base_path('routes/api');
                $apiVersions = [];
                if (is_dir($apiRoutesPath)) {
                    $directories = glob($apiRoutesPath . '/v*', GLOB_ONLYDIR);
                    foreach ($directories as $directory) {
                        $version = basename($directory);
                        if (preg_match('/^v\d+$/', $version)) {
                            $apiVersions[] = $version;
                        }
                    }
                    sort($apiVersions); // Sort versions (v1, v2, v3, etc.)
                }
                
                foreach ($apiVersions as $version) {
                    $versionPath = base_path("routes/api/{$version}");
                    if (is_dir($versionPath)) {
                        Route::prefix($version)->group(function () use ($loadVersionRoutes, $version) {
                            // Dynamically load all module routes for this version
                            $loadVersionRoutes($version);
                        });
                    }
                }
                
                // // API fallback route
                // Route::fallback(function () {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'API endpoint not found',
                //         'code' => 404
                //     ], 404);
                // });
            });
            
            // Load admin routes
            Route::prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->redirectGuestsTo(fn (Request $request) =>
            $request->routeIs('admin.*') ? route('admin.loginPage') : null
        );

        $middleware->use([
            \Illuminate\Session\Middleware\StartSession::class,  // Ensure this is listed
            \Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks::class,
            // \Illuminate\Http\Middleware\TrustHosts::class,
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Register API middleware aliases
        $middleware->alias([
            'admin.api' => \App\Http\Middleware\AdminApiMiddleware::class,
            'api.lang' => \App\Http\Middleware\ApiLangMiddleware::class,
            'complete.info' => \App\Http\Middleware\CheckCompleteInfo::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    require_once app_path('Helpers/MainHelper.php');

    return $app;
