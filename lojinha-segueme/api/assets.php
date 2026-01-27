<?php
/**
 * Static asset server for Vercel
 * Serves CSS, JS, and other static files from the build directory
 */

$basePath = __DIR__ . '/..';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

// Remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Debug mode - add ?debug=1 to see info
if (isset($_GET['debug'])) {
    header('Content-Type: text/plain');
    echo "Request URI: " . $requestUri . "\n";
    echo "Path: " . $path . "\n";
    echo "Base Path: " . $basePath . "\n";
    echo "Real Base Path: " . realpath($basePath) . "\n";
    echo "\nChecking locations:\n";
    
    $locations = [
        $basePath . $path,
        $basePath . '/public' . $path,
        $basePath . '/build' . str_replace('/build', '', $path),
        $basePath . '/public/build' . str_replace('/build', '', $path),
    ];
    
    foreach ($locations as $loc) {
        echo "- $loc: " . (file_exists($loc) ? "EXISTS" : "NOT FOUND") . "\n";
    }
    
    echo "\nDirectory listing of build/:\n";
    $buildDir = $basePath . '/build';
    if (is_dir($buildDir)) {
        foreach (scandir($buildDir) as $file) {
            echo "  - $file\n";
        }
    } else {
        echo "  Directory not found\n";
    }
    
    echo "\nDirectory listing of public/build/:\n";
    $publicBuildDir = $basePath . '/public/build';
    if (is_dir($publicBuildDir)) {
        foreach (scandir($publicBuildDir) as $file) {
            echo "  - $file\n";
        }
    } else {
        echo "  Directory not found\n";
    }
    exit;
}

// Security: only allow specific file types
$allowedExtensions = ['css', 'js', 'map', 'ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot'];

// Get file extension
$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    exit('Forbidden');
}

// Determine the file path - try multiple locations
$possiblePaths = [
    $basePath . $path,                                    // /build/assets/...
    $basePath . '/public' . $path,                        // /public/build/assets/...
];

$filePath = null;
foreach ($possiblePaths as $tryPath) {
    if (file_exists($tryPath)) {
        $filePath = $tryPath;
        break;
    }
}

// Check if file exists
if (!$filePath) {
    http_response_code(404);
    exit('File not found: ' . $path . ' (tried: ' . implode(', ', $possiblePaths) . ')');
}

// Set content type
$mimeTypes = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'map' => 'application/json',
    'ico' => 'image/x-icon',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'eot' => 'application/vnd.ms-fontobject',
];

$contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
header('Content-Type: ' . $contentType);

// Cache headers for assets
if (in_array($extension, ['css', 'js', 'woff', 'woff2', 'ttf', 'eot'])) {
    header('Cache-Control: public, max-age=31536000, immutable');
}

// Output file contents
readfile($filePath);
