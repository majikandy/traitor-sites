<?php
// Router for PHP built-in dev server: php -S localhost:8181 router.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// Serve real static files (CSS, images, etc.) as-is
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Try exact .php match
if (is_file($file . '.php')) {
    require $file . '.php';
    exit;
}

// Try directory index
if (is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (is_file($index)) {
        require $index;
        exit;
    }
}

http_response_code(404);
echo '<h1>404 Not Found</h1>';
