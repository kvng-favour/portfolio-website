<?php
/**
 * Core app configuration. Loads /hprs/.env (git-ignored) if present,
 * falling back to the values below for local development.
 */

function hprs_load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key !== '' && getenv($key) === false) {
            putenv("$key=$value");
        }
    }
}

hprs_load_env(dirname(__DIR__) . '/.env');

define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');
define('APP_NAME', 'Hospitality Recruitment and Placement');

// Base path the app is served from (e.g. "/hprs" during local dev inside
// the portfolio repo). Used to build internal links/asset paths.
define('BASE_PATH', '/hprs');

if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
