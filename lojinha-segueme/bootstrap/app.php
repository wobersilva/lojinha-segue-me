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
    // Use /tmp for writable storage in serverless environment
    $app->useStoragePath('/tmp/storage');
    
    // Create necessary directories in /tmp
    $directories = [
        '/tmp/storage',
        '/tmp/storage/app',
        '/tmp/storage/framework',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];
    
    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}

return $app;
