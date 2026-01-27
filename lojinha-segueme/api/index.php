<?php
// Simple test - if you see this, PHP is working
echo "PHP OK - ";
echo date('Y-m-d H:i:s');
exit;

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
