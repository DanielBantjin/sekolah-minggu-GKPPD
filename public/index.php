<?php

define('LARAVEL_START', microtime(true));

echo "1<br>";

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

echo "2<br>";

require __DIR__.'/../vendor/autoload.php';

echo "3<br>";

$app = require_once __DIR__.'/../bootstrap/app.php';

echo "4<br>";

$app->handleRequest(Illuminate\Http\Request::capture());

echo "5";