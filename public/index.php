<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// cPanel Compatibility - Check for different possible paths
$possiblePaths = [
    __DIR__.'/../storage/framework/maintenance.php',
    __DIR__.'/storage/framework/maintenance.php',
    dirname(__DIR__).'/storage/framework/maintenance.php'
];

$maintenance = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $maintenance = $path;
        break;
    }
}

// Determine if the application is in maintenance mode...
if ($maintenance) {
    require $maintenance;
}

// Register the Composer autoloader...
$autoloadPaths = [
    __DIR__.'/../vendor/autoload.php',
    __DIR__.'/vendor/autoload.php',
    dirname(__DIR__).'/vendor/autoload.php'
];

$autoload = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoload = $path;
        break;
    }
}

if (!$autoload) {
    die('Composer autoload file not found. Please run: composer install');
}

require $autoload;

// Bootstrap Laravel and handle the request...
$bootstrapPaths = [
    __DIR__.'/../bootstrap/app.php',
    __DIR__.'/bootstrap/app.php',
    dirname(__DIR__).'/bootstrap/app.php'
];

$bootstrap = null;
foreach ($bootstrapPaths as $path) {
    if (file_exists($path)) {
        $bootstrap = $path;
        break;
    }
}

if (!$bootstrap) {
    die('Bootstrap file not found. Please check your Laravel installation.');
}

/** @var Application $app */
$app = require_once $bootstrap;

$app->handleRequest(Request::capture());
