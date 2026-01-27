<?php
/**
 * Static asset server for Vercel
 * Serves CSS, JS, and other static files from the build directory
 */

$basePath = __DIR__ . '/..';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

// Remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Security: only allow specific file types
$allowedExtensions = ['css', 'js', 'map', 'ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot'];

// Get file extension
$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    exit('Forbidden');
}

// Determine the file path
if (strpos($path, '/build/') === 0) {
    // Files from build directory (Vite output)
    $filePath = $basePath . $path;
} elseif (strpos($path, '/images/') === 0) {
    // Files from images directory
    $filePath = $basePath . '/public' . $path;
} elseif ($path === '/favicon.ico') {
    $filePath = $basePath . '/public/favicon.ico';
} else {
    // Try public directory
    $filePath = $basePath . '/public' . $path;
}

// Check if file exists
if (!file_exists($filePath)) {
    http_response_code(404);
    exit('File not found: ' . $path);
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
