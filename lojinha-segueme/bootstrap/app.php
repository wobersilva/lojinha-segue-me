<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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
        // In production/serverless, render exceptions as JSON to avoid view errors
        if (getenv('APP_ENV') === 'production') {
            $exceptions->render(function (\Throwable $e, Request $request) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString()),
                ], 500);
            });
        }
    })->create();

// Configure storage paths for Vercel (serverless/read-only filesystem)
if (isset($_ENV['VERCEL']) || getenv('VERCEL') || getenv('APP_ENV') === 'production') {
    // Use /tmp for writable storage in serverless environment
    $app->useStoragePath('/tmp/storage');
}

return $app;
