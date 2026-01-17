#!/bin/bash

# Create logs directory if it doesn't exist
mkdir -p .devcontainer/logs

# Generate timestamp for log file
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
LOG_FILE=".devcontainer/logs/diagnostics-${TIMESTAMP}.txt"

# Redirect all output to log file
exec > >(tee -a "$LOG_FILE")
exec 2>&1

echo "========================================="
echo "CODESPACES DIAGNOSTICS REPORT"
echo "Generated: $(date)"
echo "========================================="
echo ""

echo "=== SYSTEM INFORMATION ==="
uname -a
echo ""

echo "=== PHP VERSION ==="
php -v
echo ""

echo "=== PHP EXTENSIONS ==="
php -m
echo ""

echo "=== COMPOSER VERSION ==="
composer --version 2>/dev/null || echo "Composer not found in PATH"
echo ""

echo "=== NGINX STATUS ==="
if command -v nginx &> /dev/null; then
    nginx -v 2>&1
    nginx -t 2>&1 || echo "Nginx configuration test failed"
else
    echo "Nginx binary not found"
fi
echo ""

echo "=== CONFIGURATION CHECKS ==="

# Check base_url in config.php
if [ -f "mvc/config/config.php" ]; then
    echo "Checking mvc/config/config.php for base_url..."
    grep -n "base_url" mvc/config/config.php | head -5 || echo "base_url not found"
    echo ""
    
    echo "Checking mvc/config/config.php for cookie_domain..."
    grep -n "cookie_domain" mvc/config/config.php | head -5 || echo "cookie_domain not found"
    echo ""
else
    echo "WARNING: mvc/config/config.php not found"
    echo ""
fi

# Check database connectivity
echo "=== DATABASE CONNECTIVITY ==="
if command -v mysql &> /dev/null; then
    if mysql -h mariadb -u shule -pshule -e "SELECT VERSION();" 2>/dev/null; then
        echo "✓ Database connection successful"
        mysql -h mariadb -u shule -pshule -e "SHOW DATABASES;" 2>/dev/null
    else
        echo "✗ Database connection failed"
    fi
else
    echo "MySQL client not installed"
fi
echo ""

# Check Redis connectivity
echo "=== REDIS CONNECTIVITY ==="
if command -v redis-cli &> /dev/null; then
    if redis-cli -h redis ping 2>/dev/null | grep -q "PONG"; then
        echo "✓ Redis connection successful"
    else
        echo "✗ Redis connection failed"
    fi
else
    echo "Redis CLI not installed, trying PHP extension..."
    timeout 5 php -r '
    if (extension_loaded("redis")) {
        try {
            $redis = new Redis();
            $redis->connect("redis", 6379, 2);  // 2 second timeout
            echo "✓ Redis connection successful via PHP" . PHP_EOL;
        } catch (Exception $e) {
            echo "✗ Redis connection failed: " . $e->getMessage() . PHP_EOL;
        }
    } else {
        echo "Redis extension not loaded" . PHP_EOL;
    }
    ' 2>/dev/null || echo "Redis check timed out or failed"
fi
echo ""

# Check web server response
echo "=== WEB SERVER CHECK ==="
if command -v curl &> /dev/null; then
    echo "Testing localhost..."
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" != "000" ]; then
        echo "✓ Web server responding with HTTP $HTTP_CODE"
    else
        echo "✗ Web server not responding"
    fi
else
    echo "curl not installed, skipping web server check"
fi
echo ""

# Check directory permissions
echo "=== DIRECTORY PERMISSIONS ==="
for dir in mvc/uploads mvc/cache mvc/logs mvc/config; do
    if [ -d "$dir" ]; then
        PERMS=$(ls -ld "$dir" | awk '{print $1}')
        echo "$dir: $PERMS"
    else
        echo "$dir: NOT FOUND"
    fi
done
echo ""

# Check vendor directory
echo "=== COMPOSER DEPENDENCIES ==="
if [ -d "vendor" ]; then
    echo "✓ vendor/ directory exists"
    echo "Installed packages:"
    composer show 2>/dev/null | head -10 || echo "Unable to list packages"
else
    echo "✗ vendor/ directory not found - run 'composer install'"
fi
echo ""

echo "========================================="
echo "DIAGNOSTICS COMPLETE"
echo "Log saved to: $LOG_FILE"
echo "========================================="
