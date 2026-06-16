<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Hostinger shared hosting — Laravel bootstrap
|--------------------------------------------------------------------------
|
| Place this file in public_html. Laravel core stays outside the web root.
|
| Supported layouts (auto-detected):
|   1. ../laravel/artisan          → domains/example.com/laravel + public_html
|   2. ../../indiannepali/artisan  → ~/indiannepali + domains/.../public_html
|   3. ../artisan                  → Laravel root is parent of public_html
|
| If hPanel lets you set document root to .../public, use public/index.php
| instead (standard Laravel — no changes needed).
|
*/

$laravelRoot = null;

foreach ([
    dirname(__DIR__).'/laravel',
    dirname(__DIR__, 2).'/indiannepali',
    dirname(__DIR__),
] as $candidate) {
    if (is_file($candidate.'/artisan')) {
        $laravelRoot = realpath($candidate) ?: $candidate;
        break;
    }
}

if ($laravelRoot === null) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("Laravel root not found. See deploy/HOSTINGER_DEPLOYMENT_REPORT.md\n");
}

if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravelRoot.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $laravelRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
