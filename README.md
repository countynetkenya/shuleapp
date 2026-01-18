# ShuleApp School Management System

A robust school management system built with CodeIgniter 3, designed for portability and performance.

## Requirements
- **OS**: Ubuntu 22.04 LTS (Recommended) / Debian
- **Web Server**: Nginx (Recommended) or Apache
- **PHP**: 8.3+ (Extensions: intl, gd, curl, mbstring, xml, zip, mysqli)
- **Database**: MariaDB 10.11+

## Installation

1. **Clone & Setup**
   ```bash
   git clone https://github.com/countynetkenya/shuleapp.git
   cd shuleapp
   cp .env.example .env
   # Edit .env with your DB credentials
   ```

2. **Dependencies**
   ```bash
   composer install
   ```

3. **Database**
   Import the schema (if available) or ensure the database named in `.env` exists.

## Production Optimization (Nginx + MariaDB + Ubuntu 22)

### 1. Nginx Configuration
For CodeIgniter on Nginx, use the following server block structure to handle URL rewriting properly:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/shuleapp;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Optimization: Cache Static Assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    # Security: Deny access to sensitive files
    location ~ ^/(application|system|tests|vendor|docs)/ {
        deny all;
        return 403;
    }
    location ~ /\.env { deny all; }
}
```

### 2. MariaDB Optimization
Edit `/etc/mysql/mariadb.conf.d/50-server.cnf`:

- **InnoDB Buffer Pool**: Set to 60-70% of available RAM.
  ```ini
  innodb_buffer_pool_size = 1G  # Adjust based on server RAM
  ```
- **Query Cache**: (Optional, check if beneficial for your read-heavy workload)
  ```ini
  query_cache_type = 1
  query_cache_size = 64M
  ```

### 3. Ubuntu 22.04 Setup
Install the necessary stack for maximum performance:

```bash
sudo apt update
sudo apt install nginx mariadb-server php8.3-fpm php8.3-mysql php8.3-intl php8.3-gd php8.3-zip php8.3-mbstring composer
```

## Development
- **Beta Branch**: All development happens on `beta`.
- **Main Branch**: Only stable releases.
- **Troubleshooting**: Check `docs/BUGS.md` or `application/logs/`.
