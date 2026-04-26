<?php

use App\Http\Middleware\Role;
use App\Http\Middleware\TrainerApproved;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render runs Laravel behind a proxy; trust forwarded headers for correct HTTPS URL generation.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => Role::class,
            'trainer.approved' => TrainerApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
