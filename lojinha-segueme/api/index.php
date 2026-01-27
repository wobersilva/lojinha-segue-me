<?php

// Enable error reporting and display
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Start output buffering to catch any errors
ob_start();

try {
    // Define base path
    $basePath = __DIR__ . '/..';
    
    echo "<!-- Debug: Base path = " . $basePath . " -->\n";
    
    // Check if vendor exists
    if (!file_exists($basePath . '/vendor/autoload.php')) {
        ob_end_clean();
        http_response_code(500);
        ?>
        <!DOCTYPE html>
        <html>
        <head><title>Error: Composer Not Installed</title></head>
        <body style="font-family: Arial; padding: 20px;">
            <h1>❌ Error: Composer dependencies not installed</h1>
            <p>The vendor directory was not found.</p>
            <p><strong>Expected path:</strong> <?= $basePath ?>/vendor/autoload.php</p>
            
            <h2>Files in base directory:</h2>
            <ul>
                <?php foreach (scandir($basePath) as $file) { ?>
                    <li><?= $file ?></li>
                <?php } ?>
            </ul>
            
            <h2>Environment Variables:</h2>
            <ul>
                <li>APP_ENV: <?= getenv('APP_ENV') ?: 'NOT SET' ?></li>
                <li>APP_KEY: <?= getenv('APP_KEY') ? 'SET (length: ' . strlen(getenv('APP_KEY')) . ')' : 'NOT SET' ?></li>
                <li>DB_HOST: <?= getenv('DB_HOST') ?: 'NOT SET' ?></li>
            </ul>
            
            <p><small>If vendor/ is missing, the build command did not run. Check Vercel build logs.</small></p>
        </body>
        </html>
        <?php
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
    
    // Clear the buffer before Laravel handles the request
    ob_end_clean();
    
    // Handle the request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $response->send();
    
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Application Error</title></head>
    <body style="font-family: Arial; padding: 20px;">
        <h1>🔴 Application Error</h1>
        <p><strong>Message:</strong> <?= htmlspecialchars($e->getMessage()) ?></p>
        <p><strong>File:</strong> <?= htmlspecialchars($e->getFile()) ?></p>
        <p><strong>Line:</strong> <?= $e->getLine() ?></p>
        
        <h2>Stack Trace:</h2>
        <pre style="background: #f5f5f5; padding: 10px; overflow: auto;"><?= htmlspecialchars($e->getTraceAsString()) ?></pre>
        
        <h2>Environment Check:</h2>
        <ul>
            <li>PHP Version: <?= PHP_VERSION ?></li>
            <li>APP_KEY set: <?= getenv('APP_KEY') ? 'Yes' : 'No' ?></li>
            <li>DB_HOST: <?= getenv('DB_HOST') ?: 'NOT SET' ?></li>
        </ul>
    </body>
    </html>
    <?php
    exit(1);
}
