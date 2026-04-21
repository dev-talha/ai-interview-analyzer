<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Helpers/functions.php';
loadEnv(__DIR__ . '/../.env');

date_default_timezone_set(env('TIMEZONE', 'Asia/Dhaka'));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});