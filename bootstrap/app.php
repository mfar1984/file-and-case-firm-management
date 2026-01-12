<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'webhook' => \App\Http\Middleware\WebhookMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'firm.scope' => \App\Http\Middleware\FirmScope::class,
            'firm.switch' => \App\Http\Middleware\FirmSwitchDetection::class,
            'dynamic.cors' => \App\Http\Middleware\DynamicCorsMiddleware::class,
        ]);

        // Apply FirmScope middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\FirmScope::class,
            \App\Http\Middleware\FirmSwitchDetection::class,
        ]);

        // Apply Dynamic CORS middleware to API routes
        $middleware->api(prepend: [
            \App\Http\Middleware\DynamicCorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
