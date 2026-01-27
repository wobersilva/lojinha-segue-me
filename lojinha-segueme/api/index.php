<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$basePath = __DIR__ . '/..';

require $basePath . '/vendor/autoload.php';

define('LARAVEL_START', microtime(true));

try {
    $app = require_once $basePath . '/bootstrap/app.php';
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $response->send();
    
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Laravel Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    
    echo "<h3>Environment Variables:</h3>";
    echo "<ul>";
    echo "<li>APP_KEY: " . (getenv('APP_KEY') ? 'SET (' . strlen(getenv('APP_KEY')) . ' chars)' : '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>APP_ENV: " . (getenv('APP_ENV') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>DB_HOST: " . (getenv('DB_HOST') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>DB_DATABASE: " . (getenv('DB_DATABASE') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "</ul>";
}
