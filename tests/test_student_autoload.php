<?php

// Mocking CodeIgniter Setup for standalone testing
if (!defined('BASEPATH')) define('BASEPATH', 'system/');
if (!defined('APPPATH')) define('APPPATH', __DIR__ . '/../mvc/');
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');

function show_error($message, $code) {
    echo "Show Error called: [$code] $message\n";
    exit(1); // Simulate exit
}

echo "Testing Student Controller logic...\n";

// Test 1: Simulate missing vendor
echo "Test 1: Missing vendor/autoload.php\n";
// Rename vendor if exists to simulate missing
$vendorPath = __DIR__ . '/../vendor/autoload.php';
$renamed = false;
if (file_exists($vendorPath)) {
    rename($vendorPath, $vendorPath . '.bak');
    $renamed = true;
}

$logicTest = function() {
    $path = APPPATH . '../vendor/autoload.php';
    echo "Checking path: " . $path . "\n";
    echo "Realpath: " . realpath($path) . "\n";
    if (file_exists($path)) {
        echo "Found vendor.\n";
    } else {
        echo "Vendor missing logic triggered.\n";
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo "Die message would be shown.\n";
        } else {
            show_error("System dependency missing.", 500);
        }
    }
};

$logicTest();

// Test 2: Simulate present vendor
echo "\nTest 2: Present vendor/autoload.php\n";
// Create dummy vendor
$dummyDir = __DIR__ . '/../vendor';
if (!is_dir($dummyDir)) mkdir($dummyDir);
file_put_contents($dummyDir . '/autoload.php', "<?php // Dummy autoload");

$logicTest();

// Cleanup
if (file_exists($dummyDir . '/autoload.php')) {
    unlink($dummyDir . '/autoload.php');
}
if ($renamed) {
    rename($vendorPath . '.bak', $vendorPath);
}

echo "\nTests Completed.\n";
