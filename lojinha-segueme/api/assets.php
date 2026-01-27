<?php
/**
 * Static asset server for Vercel
 */

$basePath = __DIR__ . '/..';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);

// Debug mode
if (isset($_GET['debug'])) {
    header('Content-Type: text/plain');
    echo "Path: $path\n";
    echo "Base: $basePath\n";
    $testFile = $basePath . $path;
    echo "File: $testFile\n";
    echo "Exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
    if (file_exists($testFile)) {
        echo "Size: " . filesize($testFile) . " bytes\n";
        echo "First 500 chars:\n" . substr(file_get_contents($testFile), 0, 500);
    }
    exit;
}

// Allowed extensions
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$allowed = ['css', 'js', 'map', 'ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot'];

if (!in_array($ext, $allowed)) {
    http_response_code(403);
    die('Forbidden');
}

// Find file
$filePath = null;
$tryPaths = [
    $basePath . $path,
    $basePath . '/public' . $path,
];

foreach ($tryPaths as $try) {
    if (file_exists($try)) {
        $filePath = $try;
        break;
    }
}

if (!$filePath) {
    http_response_code(404);
    die('Not found: ' . $path);
}

// MIME types
$mimes = [
    'css'   => 'text/css; charset=utf-8',
    'js'    => 'application/javascript; charset=utf-8',
    'map'   => 'application/json',
    'ico'   => 'image/x-icon',
    'png'   => 'image/png',
    'jpg'   => 'image/jpeg',
    'jpeg'  => 'image/jpeg',
    'gif'   => 'image/gif',
    'svg'   => 'image/svg+xml',
    'woff'  => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf'   => 'font/ttf',
    'eot'   => 'application/vnd.ms-fontobject',
];

// Clear any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Send headers
header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: public, max-age=31536000, immutable');

// Send file
readfile($filePath);
exit;
