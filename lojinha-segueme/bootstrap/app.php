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

// Configure paths for Vercel (serverless/read-only filesystem)
if (isset($_ENV['VERCEL']) || getenv('VERCEL') || getenv('APP_ENV') === 'production') {
    // Create writable directories in /tmp
    $tmpDirs = [
        '/tmp/storage',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions', 
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
        '/tmp/bootstrap/cache',
    ];
    foreach ($tmpDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    
    // Use /tmp for writable directories
    $app->useStoragePath('/tmp/storage');
    
    // Override bootstrap cache path
    $app->instance('path.bootstrap.cache', '/tmp/bootstrap/cache');
    
    // Manually register core providers that may not auto-load in serverless
    $app->register(\Illuminate\View\ViewServiceProvider::class);
    $app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
}

return $app;
