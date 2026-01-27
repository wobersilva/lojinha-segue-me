<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

// Output buffering to catch any output
ob_start();

try {
    // Check critical paths
    $basePath = __DIR__ . '/..';
    $autoloadPath = $basePath . '/vendor/autoload.php';
    $bootstrapPath = $basePath . '/bootstrap/app.php';
    
    // Debug: Check if files exist
    if (!file_exists($autoloadPath)) {
        http_response_code(500);
        echo "ERROR: Composer autoload not found!\n";
        echo "Looking for: {$autoloadPath}\n";
        echo "Directory contents of base:\n";
        echo implode("\n", scandir($basePath));
        exit(1);
    }
    
    if (!file_exists($bootstrapPath)) {
        http_response_code(500);
        echo "ERROR: Bootstrap file not found!\n";
        echo "Looking for: {$bootstrapPath}\n";
        exit(1);
    }
    
    // Define LARAVEL_START
    define('LARAVEL_START', microtime(true));
    
    // Check maintenance mode
    if (file_exists($maintenance = $basePath . '/storage/framework/maintenance.php')) {
        require $maintenance;
    }
    
    // Load Composer autoloader
    require $autoloadPath;
    
    // Bootstrap Laravel
    $app = require_once $bootstrapPath;
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    // Flush any buffered output first
    ob_end_clean();
    
    $response->send();
    
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
    echo "<h1>Application Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</body></html>";
    exit(1);
}
