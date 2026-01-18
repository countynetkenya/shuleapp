# Deployment Runbook

## Overview
This document outlines the steps to deploy the application.

## Prerequisites
- PHP 8.3+
- Composer
- MariaDB 10.11+
- Web Server (Apache/Nginx)

## Standard Deployment
1.  **Pull Code**:
    ```bash
    git pull origin main
    ```

2.  **Install Dependencies**:
    The application relies on third-party libraries located in `vendor/`. You must run composer to install them.
    ```bash
    composer install --no-dev --optimize-autoloader
    ```

3.  **Database Updates**:
    Run any pending migrations or SQL scripts.

4.  **Permissions**:
    Ensure the web server has write access to `uploads/` and `application/logs/`.

## Troubleshooting
### /student or other pages failing
If pages like `/student` return a 500 error, it is likely that `vendor/autoload.php` is missing. Ensure step 2 is completed.
