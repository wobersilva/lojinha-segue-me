<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Configure storage paths for Vercel (serverless/read-only filesystem)
if (isset($_ENV['VERCEL']) || getenv('VERCEL') || getenv('APP_ENV') === 'production') {
    $app->useStoragePath('/tmp/storage');
    
    // Manually register core providers that may not auto-load in serverless
    $app->register(\Illuminate\View\ViewServiceProvider::class);
    $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
}

return $app;
