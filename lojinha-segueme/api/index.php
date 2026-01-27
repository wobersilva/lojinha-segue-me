<?php
echo "Step 1: PHP working<br>";

$basePath = __DIR__ . '/..';
echo "Step 2: Base path = " . realpath($basePath) . "<br>";

echo "Step 3: Files in directory:<br>";
$files = scandir($basePath);
foreach ($files as $f) {
    echo "- " . $f . "<br>";
}

$vendorExists = file_exists($basePath . '/vendor/autoload.php');
echo "Step 4: vendor/autoload.php exists = " . ($vendorExists ? "YES" : "NO") . "<br>";

if (!$vendorExists) {
    echo "<br><h2>ERROR: Composer not installed!</h2>";
    echo "<p>The build command did not install Composer dependencies.</p>";
    exit;
}

echo "Step 5: Loading autoloader...<br>";
require $basePath . '/vendor/autoload.php';

echo "Step 6: Autoloader loaded!<br>";

echo "Step 7: Loading bootstrap/app.php...<br>";
$app = require_once $basePath . '/bootstrap/app.php';

echo "Step 8: App loaded! Starting Laravel...<br>";

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());
$response->send();
$kernel->terminate($request, $response);
