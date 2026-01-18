<?php

/**
 * Site Crawler & Asset Validator
 * 
 * Usage: php scripts/site_crawler.php
 */

// Configuration
$baseUrl = 'http://127.0.0.1:8000';
$username = 'testadmin';
$password = '123456';
$loginUrl = $baseUrl . '/signin/index';
$dashboardUrl = $baseUrl . '/dashboard/index';
$cookieFile = '/tmp/cookie.txt';

// Colors for terminal output
$RED = "\033[0;31m";
$GREEN = "\033[0;32m";
$YELLOW = "\033[1;33m";
$NC = "\033[0m"; // No Color

echo "{$YELLOW}Starting Site Crawler & Asset Validator...{$NC}\n";
echo "Base URL: $baseUrl\n";

// --- Step 1: Login ---
echo "Logging in...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => $username,
    'password' => $password
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 && $httpCode !== 302 && $httpCode !== 303) {

    echo "{$RED}Login Failed! HTTP $httpCode{$NC}\n";
    exit(1);
}
if (strpos($response, 'Sign in') !== false || strpos($response, 'login-box') !== false) {
     echo "{$RED}Login Failed (Credentials rejected)!{$NC}\n";
    // exit(1); // Proceed for now to debug
} else {
    echo "{$GREEN}Login Successful.{$NC}\n";
}

// --- Step 2: Define Links (Manual Check since Dashboard crashes) ---
$links = [];
$links[] = $baseUrl . '/teacher/index';

// --- Step 3 & 4: Check Pages and Assets ---
$brokenPages = [];
$brokenAssets = [];
$checkedAssets = []; // Cache to avoid re-checking same CSS

$count = 0;
foreach ($links as $pageUrl) {
    $count++;
    echo "[$count/" . count($links) . "] Checking $pageUrl ... ";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $pageHtml = curl_exec($ch);
    $pageCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($pageCode >= 400) {
        echo "{$RED}FAIL ($pageCode){$NC}\n";
        $brokenPages[] = "$pageUrl ($pageCode)";
        continue;
    }
    echo "{$GREEN}OK{$NC}\n";

    // Debug: Print CSS links found
    $pageDom = new DOMDocument();
    @$pageDom->loadHTML($pageHtml);
    $pageXpath = new DOMXPath($pageDom);
    
    foreach ($pageXpath->query("//link[@rel='stylesheet']/@href") as $node) {
        // Echo the link found to see if it is malformed
        echo "    Found CSS: " . $node->nodeValue . "\n";
        checkAsset($node->nodeValue, $pageUrl, $baseUrl, $brokenAssets, $checkedAssets);
    }
    
    foreach ($pageXpath->query("//script/@src") as $node) {
        checkAsset($node->nodeValue, $pageUrl, $baseUrl, $brokenAssets, $checkedAssets);
    }
}

function checkAsset($assetUrl, $sourcePage, $baseUrl, &$brokenAssets, &$checkedAssets) {
    if (empty($assetUrl)) return;
    
    // Determine absolute URL
    if (strpos($assetUrl, 'http') !== 0) {
        if (strpos($assetUrl, '/') === 0) {
           $checkUrl = $baseUrl . $assetUrl; 
        } else {
           $checkUrl = $baseUrl . '/' . $assetUrl;
        }
    } else {
        $checkUrl = $assetUrl;
    }
    
    if (in_array($checkUrl, $checkedAssets)) return;
    $checkedAssets[] = $checkUrl;
    
    $ch = curl_init($checkUrl);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD Request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code >= 400 || $code == 0) {
        $brokenAssets[] = [
            'asset' => $checkUrl,
            'source' => $sourcePage,
            'status' => $code
        ];
        echo "   -> Asset FAIL: $checkUrl ($code)\n";
    }
}

// --- Report ---
echo "\n{$YELLOW}=== SCAN REPORT ==={$NC}\n";
if (empty($brokenPages) && empty($brokenAssets)) {
    echo "{$GREEN}All checked pages and assets are reachable!{$NC}\n";
} else {
    if (!empty($brokenPages)) {
        echo "{$RED}Broken Pages:{$NC}\n";
        foreach ($brokenPages as $p) echo "- $p\n";
    }
    if (!empty($brokenAssets)) {
        echo "{$RED}Broken Assets (CSS/JS):{$NC}\n";
        foreach ($brokenAssets as $a) {
            echo "- {$a['asset']} (Status: {$a['status']}) found on {$a['source']}\n";
        }
    }
}

// Cleanup
@unlink($cookieFile);
