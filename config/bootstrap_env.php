<?php
// Simple .env loader — safe, minimal, no external deps
// Loads KEY=VALUE lines into getenv() / $_ENV / $_SERVER when present

if (PHP_SAPI === 'cli' || PHP_SAPI === 'cli-server' || !defined('ENVIRONMENT') ) {
    // ok to load early
}

$root = dirname(__DIR__);
$envFile = $root . DIRECTORY_SEPARATOR . '.env';
if (!file_exists($envFile)) {
    return;
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || strpos($line, '#') === 0) {
        continue;
    }
    if (!strpos($line, '=')) {
        continue;
    }
    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value, " \t\n\r\0\x0B\"'");
    if ($name === '') continue;
    if (getenv($name) === false) {
        putenv("$name=$value");
    }
    if (!isset($_ENV[$name])) $_ENV[$name] = $value;
    if (!isset($_SERVER[$name])) $_SERVER[$name] = $value;
}

return;
