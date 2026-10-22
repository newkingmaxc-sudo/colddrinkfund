<?php

ini_set('max_execution_time', 180);
@set_time_limit(180);

$publicPath = __DIR__ . '/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Remove leading /public if present
$cleanUri = preg_replace('#^/public/#', '/', $uri);

$filePath = $publicPath . $cleanUri;

// If it's a real static file, serve it directly with the correct Content-Type header
if ($cleanUri !== '/' && is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'json'  => 'application/json',
        'webp'  => 'image/webp',
        'map'   => 'application/json',
        'txt'   => 'text/plain',
    ];

    if (isset($mimes[$ext])) {
        header("Content-Type: " . $mimes[$ext]);
    }
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: public, max-age=604800, stale-while-revalidate=86400");
    
    $lastModified = filemtime($filePath);
    $etag = dechex($lastModified) . '-' . dechex(filesize($filePath));
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
    header("ETag: \"$etag\"");

    if ((isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === "\"$etag\"") ||
        (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified)) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }

    header("Content-Length: " . filesize($filePath));
    readfile($filePath);
    exit;
}

require_once $publicPath . '/index.php';
