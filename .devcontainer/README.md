# GitHub Codespaces Dev Environment

This directory contains the configuration for the GitHub Codespaces development environment for the ShuleApp CodeIgniter 3 application.

## Overview

The dev environment provides a complete LEMP stack (Linux, Nginx, MariaDB, PHP) with additional services:

- **PHP 8.1-FPM** with required extensions
- **Nginx** web server
- **MariaDB 10.6** database
- **Redis** for caching
- **MailHog** for email testing
- **Composer** for dependency management

## Files

- `devcontainer.json` - Main Codespaces configuration
- `docker-compose.yml` - Multi-service container orchestration
- `Dockerfile` - PHP-FPM + Nginx container image
- `nginx/default.conf` - Nginx web server configuration
- `postCreate.sh` - Post-creation initialization script
- `diagnostics.sh` - System health check and diagnostics script

## Services

### Workspace (PHP-FPM + Nginx)
- Port: 80
- Root: `/workspaces/shuleapp`
- PHP extensions: pdo_mysql, mysqli, mbstring, zip, gd, xml, opcache, redis

### MariaDB
- Port: 3306
- Database: `shule`
- User: `shule`
- Password: `shule`
- Root password: `root`

### Redis
- Port: 6379
- Image: redis:alpine

### MailHog
- Web UI: http://localhost:8025
- SMTP: localhost:1025

## Usage

### First Time Setup

When you open this repository in Codespaces, the following happens automatically:

1. Docker containers are built and started
2. Composer dependencies are installed
3. MariaDB is initialized with the `shule` database
4. Required directories (`mvc/uploads`, `mvc/cache`, `mvc/logs`) are created with proper permissions
5. Diagnostics script runs to verify the setup

### Accessing the Application

- **Web Application**: http://localhost (or the forwarded Codespaces URL)
- **MailHog UI**: http://localhost:8025
- **Database**: Connect to `mariadb:3306` from within Codespaces

### Running Diagnostics

To manually run diagnostics:

```bash
bash .devcontainer/diagnostics.sh
```

Diagnostics logs are saved to `.devcontainer/logs/diagnostics-TIMESTAMP.txt`

### Database Access

From within the Codespaces terminal:

```bash
mysql -h mariadb -u shule -pshule shule
```

Or as root:

```bash
mysql -h mariadb -u root -proot
```

### Redis Access

Test Redis connection:

```bash
php -r '$r = new Redis(); $r->connect("redis", 6379); echo $r->ping();'
```

## Directory Permissions

The following directories are automatically created and set to 777 (for development only):

- `mvc/uploads` - File uploads
- `mvc/cache` - Application cache
- `mvc/logs` - Application logs
- `mvc/config` - Configuration files

## CI/CD Integration

The `.github/workflows/ai-diagnostics.yml` workflow runs daily to:

1. Execute diagnostics checks
2. Upload diagnostics reports as artifacts
3. (Optional) Use OpenAI API for intelligent analysis
4. Create GitHub issues for critical findings

To enable AI-powered analysis, add an `OPENAI_API_KEY` secret to your repository.

## Troubleshooting

### Container won't start
- Check Docker logs: `docker-compose logs workspace`
- Verify all services are running: `docker-compose ps`

### Database connection fails
- Ensure MariaDB is ready: `docker-compose logs mariadb`
- Check credentials in `mvc/config/database.php`

### Permission errors
- Run: `bash .devcontainer/postCreate.sh` to reset permissions

### Nginx errors
- Check Nginx logs: `nginx -t` (test configuration)
- View error log: `tail -f /var/log/nginx/error.log`

## Security Notes

⚠️ **Important**: This configuration is for DEVELOPMENT ONLY. Do not use in production:

- Database credentials are hardcoded
- Directory permissions are set to 777
- Debug mode may be enabled
- No SSL/TLS encryption

## Customization

### Changing PHP Version

Edit `Dockerfile` and change the base image:
```dockerfile
FROM php:8.2-fpm
```

### Adding PHP Extensions

Edit `Dockerfile` and add to the `docker-php-ext-install` line:
```dockerfile
RUN docker-php-ext-install pdo_mysql mysqli ... your_extension
```

### Modifying Nginx Configuration

Edit `nginx/default.conf` and rebuild the container.

## Support

For issues with the dev environment, check:
1. Diagnostics logs in `.devcontainer/logs/`
2. Docker container logs: `docker-compose logs`
3. GitHub Actions diagnostics workflow results
