<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set the correct paths for Vercel
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Define LARAVEL_START constant
define('LARAVEL_START', microtime(true));

// Check if vendor/autoload.php exists
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Error: Composer autoload not found at {$autoloadPath}. Run 'composer install' first.");
}

// Check if bootstrap/app.php exists
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';
if (!file_exists($bootstrapPath)) {
    die("Error: Bootstrap file not found at {$bootstrapPath}.");
}

// Determine if the application is in maintenance mode
$maintenancePath = __DIR__ . '/../storage/framework/maintenance.php';
if (file_exists($maintenancePath)) {
    require $maintenancePath;
}

// Register the Composer autoloader
require $autoloadPath;

// Bootstrap Laravel and handle the request
try {
    $app = require_once $bootstrapPath;
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $request = Illuminate\Http\Request::capture();
    
    $response = $kernel->handle($request);
    
    $response->send();
    
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    echo "Laravel Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    exit(1);
}
