<?php

if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__);
}

function assertSame($expected, $actual, $message)
{
    if ($expected !== $actual) {
        fwrite(STDERR, $message . "\nExpected: {$expected}\nActual: {$actual}\n");
        exit(1);
    }
}

function load_config()
{
    $config = [];
    include __DIR__ . '/../mvc/config/config.php';
    return $config;
}

function reset_env()
{
    putenv('APP_URL');
    putenv('COOKIE_DOMAIN');
    $_SERVER['HTTP_X_FORWARDED_PROTO'] = '';
    $_SERVER['HTTP_X_FORWARDED_HOST'] = '';
    $_SERVER['HTTP_HOST'] = '';
    $_SERVER['HTTPS'] = '';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

reset_env();
putenv('APP_URL=https://app.shulelabs.cloud');
$config = load_config();
assertSame('https://app.shulelabs.cloud/', $config['base_url'], 'APP_URL should set base_url');
assertSame('app.shulelabs.cloud', $config['cookie_domain'], 'APP_URL should set cookie_domain');

reset_env();
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
$_SERVER['HTTP_X_FORWARDED_HOST'] = 'app.shulelabs.cloud';
$config = load_config();
assertSame('https://app.shulelabs.cloud/', $config['base_url'], 'Forwarded headers should set base_url');
assertSame('app.shulelabs.cloud', $config['cookie_domain'], 'Forwarded headers should set cookie_domain');

echo "OK\n";
