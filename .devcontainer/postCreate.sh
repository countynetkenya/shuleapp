#!/bin/bash
set -e

echo "=== Post-Create Setup Started ==="

# Install Composer dependencies
if [ -f "composer.json" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "No composer.json found, skipping composer install"
fi

# Wait for MariaDB to be ready
echo "Waiting for MariaDB to be ready..."
MAX_TRIES=30
COUNT=0
until mysql -h mariadb -u shule -pshule -e "SELECT 1" > /dev/null 2>&1; do
    COUNT=$((COUNT+1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "ERROR: MariaDB did not become ready in time"
        exit 1
    fi
    echo "Waiting for database... ($COUNT/$MAX_TRIES)"
    sleep 2
done

echo "MariaDB is ready!"

# Create database if it doesn't exist
echo "Ensuring database 'shule' exists..."
mysql -h mariadb -u root -proot -e "CREATE DATABASE IF NOT EXISTS shule;" || true

# Create necessary directories and set permissions
echo "Setting up directories and permissions..."

# Create uploads directory if it doesn't exist
if [ ! -d "mvc/uploads" ]; then
    mkdir -p mvc/uploads
    echo "Created mvc/uploads directory"
fi

# Create cache directory if it doesn't exist
if [ ! -d "mvc/cache" ]; then
    mkdir -p mvc/cache
    echo "Created mvc/cache directory"
fi

# Create logs directory if it doesn't exist
if [ ! -d "mvc/logs" ]; then
    mkdir -p mvc/logs
    echo "Created mvc/logs directory"
fi

# Set permissions (777 for development environment only - NOT for production!)
# These directories need write access for the web server and development
# uploads, cache, and logs need full permissions for file operations
chmod -R 777 mvc/uploads 2>/dev/null || true
chmod -R 777 mvc/cache 2>/dev/null || true
chmod -R 777 mvc/logs 2>/dev/null || true
# Config directory gets more restrictive permissions (755) for better security
chmod -R 755 mvc/config 2>/dev/null || true

echo "Permissions set successfully"

# Run diagnostics
if [ -f ".devcontainer/diagnostics.sh" ]; then
    echo "Running diagnostics..."
    bash .devcontainer/diagnostics.sh
else
    echo "Diagnostics script not found, skipping"
fi

echo "=== Post-Create Setup Completed ==="
