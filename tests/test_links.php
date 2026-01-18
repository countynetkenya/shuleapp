<?php

$baseUrl = 'http://127.0.0.1:8000';

$urls = [
    '/',
    '/signin/index',
    '/assets/shuleapp/style.css', // Should exist
    '/assets/shuleapp/inilabs.css', // Should exist
    '/assets/shuleapp/jquery.js', // Should exist
    '/assets/inilabs/style.css', // Should NOT exist (404)
    '/install/index' // Check install page
];

echo "Testing Links on $baseUrl\n";
echo "---------------------------------------------------\n";

foreach ($urls as $path) {
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode == 0) {
        echo "[FAIL] $path : Could not connect to server.\n";
    } elseif ($httpCode >= 200 && $httpCode < 400) {
        echo "[OK]   $path : $httpCode\n";
    } elseif ($httpCode == 404) {
        if (strpos($path, 'inilabs') !== false && strpos($path, 'shuleapp') === false) {
             echo "[OK]   $path : $httpCode (Expected 404 for old path)\n";
        } else {
             echo "[FAIL] $path : $httpCode (Not Found)\n";
        }
    } else {
        echo "[WARN] $path : $httpCode\n";
    }
    
    curl_close($ch);
}

echo "---------------------------------------------------\n";

// Check if server is running
$fp = @fsockopen("127.0.0.1", 8000, $errno, $errstr, 1);
if (!$fp) {
    echo "ERROR: PHP Server is NOT running on port 8000.\n";
    echo "Please run: nohup php -S 0.0.0.0:8000 index.php > php.log 2>&1 &\n";
} else {
    fclose($fp);
}
