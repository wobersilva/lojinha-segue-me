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
        // Registra os middlewares de alias
        $middleware->alias([
            'approved' => \App\Http\Middleware\EnsureUserIsApproved::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        // Aplica o middleware de aprovação em todas as rotas autenticadas
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EnsureUserIsApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Configure for Vercel serverless
if (getenv('APP_ENV') === 'production') {
    $app->useStoragePath('/tmp/storage');
    
    // Enable debug to see errors
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = 'true';
}

return $app;
