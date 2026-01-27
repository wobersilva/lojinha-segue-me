<?php

$basePath = __DIR__ . '/..';

// Create /tmp directories for Vercel
$tmpDirs = [
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
    @mkdir($dir, 0755, true);
}

// Set environment variables for cache paths
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');

try {
    require $basePath . '/vendor/autoload.php';

    define('LARAVEL_START', microtime(true));

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
    
    if ($e->getPrevious()) {
        echo "<h3>Original Error:</h3>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getPrevious()->getMessage()) . "</p>";
    }
    
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='font-size:12px;overflow:auto;max-height:400px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
