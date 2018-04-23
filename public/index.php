<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../conf/slim/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
// DIC configuration
require __DIR__ . '/../conf/slim/dependencies.php';

// Register middleware
require __DIR__ . '/../conf/slim/middleware.php';

// Register routes
require __DIR__ . '/../conf/slim/routes.php';

// Run app
$app->run();
