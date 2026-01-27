<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define base path
$basePath = __DIR__ . '/..';

// Check if vendor exists
if (!file_exists($basePath . '/vendor/autoload.php')) {
    http_response_code(500);
    echo "<h1>Error: Composer dependencies not installed</h1>";
    echo "<p>The vendor directory was not found. This means Composer dependencies were not installed during build.</p>";
    echo "<p>Expected path: " . $basePath . "/vendor/autoload.php</p>";
    echo "<h2>Files in base directory:</h2><ul>";
    foreach (scandir($basePath) as $file) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
    exit(1);
}

// Define Laravel start time
define('LARAVEL_START', microtime(true));

// Check for maintenance mode
if (file_exists($maintenance = $basePath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
require $basePath . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once $basePath . '/bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
