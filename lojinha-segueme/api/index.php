<?php
// Disable output buffering
while (ob_get_level()) ob_end_flush();
header('Content-Type: text/html; charset=utf-8');

echo "1-PHP ";

$basePath = __DIR__ . '/..';

echo "2-DIRS ";
@mkdir('/tmp/storage/framework/views', 0755, true);
@mkdir('/tmp/bootstrap/cache', 0755, true);

echo "3-ENV ";
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');

echo "4-AUTOLOAD ";
require $basePath . '/vendor/autoload.php';

echo "5-LOADED ";
define('LARAVEL_START', microtime(true));

echo "6-BOOTSTRAP ";
$app = require_once $basePath . '/bootstrap/app.php';

echo "7-APP ";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "8-KERNEL ";
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

echo "9-DONE";
$response->send();
$kernel->terminate($request, $response);

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
    
    // Show previous exception if exists
    if ($e->getPrevious()) {
        echo "<h3>Original Error:</h3>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getPrevious()->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getPrevious()->getFile()) . ":" . $e->getPrevious()->getLine() . "</p>";
    }
    
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='font-size:12px;overflow:auto;max-height:400px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    
    echo "<h3>Environment Variables:</h3>";
    echo "<ul>";
    echo "<li>APP_KEY: " . (getenv('APP_KEY') ? 'SET (' . strlen(getenv('APP_KEY')) . ' chars)' : '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>APP_ENV: " . (getenv('APP_ENV') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>DB_HOST: " . (getenv('DB_HOST') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>DB_DATABASE: " . (getenv('DB_DATABASE') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "<li>SESSION_DRIVER: " . (getenv('SESSION_DRIVER') ?: '<strong style=\"color:red\">NOT SET</strong>') . "</li>";
    echo "</ul>";
    
    echo "<h3>/tmp directories:</h3><ul>";
    foreach ($tmpDirs as $dir) {
        echo "<li>$dir: " . (is_dir($dir) ? '<span style="color:green">EXISTS</span>' : '<span style="color:red">MISSING</span>') . "</li>";
    }
    echo "</ul>";
}
