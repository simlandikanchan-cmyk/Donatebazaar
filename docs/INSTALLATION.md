# Installation Guide

## Table of Contents

- [Requirements](#requirements)
- [Local Development](#local-development)
- [Docker Deployment](#docker-deployment)
- [Production Deployment](#production-deployment)
- [Configuration](#configuration)
- [Queue Workers](#queue-workers)
- [Cron Jobs](#cron-jobs)
- [Troubleshooting](#troubleshooting)

---

## Requirements

### Minimum System Requirements

| Component | Requirement |
|---|---|
| PHP | 8.2 or higher |
| Composer | 2.0 or higher |
| MySQL | 8.0 or higher |
| Redis | 7.0 or higher |
| Node.js | 18.0 or higher |
| NPM | 9.0 or higher |

### Required PHP Extensions

```
bcmath, ctype, curl, dom, fileinfo, gd, json, mbstring,
openssl, pdo, pdo_mysql, redis, tokenizer, xml, zip
```

Verify extensions:
```bash
php -m | grep -E "bcmath|ctype|curl|dom|fileinfo|gd|json|mbstring|openssl|pdo|redis|tokenizer|xml|zip"
```

---

## Local Development

### Step 1: Clone Repository

```bash
git clone https://github.com/your-org/donatebazaar.git
cd donatebazaar
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

For production:
```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

### Step 4: Create Environment File

```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Configure Environment

Edit `.env` file:

```env
APP_NAME=DonateBazaar
APP_ENV=local
APP_KEY=base64:xxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=donatebazaar
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

MAIL_MAILER=log

RAZORPAY_KEY=rzp_test_xxxxxxxx
RAZORPAY_SECRET=xxxxxxxx
RAZORPAY_WEBHOOK_SECRET=xxxxxxxx
```

### Step 6: Create Database

```bash
mysql -u root -p -e "CREATE DATABASE donatebazaar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 7: Run Migrations

```bash
php artisan migrate
```

### Step 8: Build Assets

For development (with hot reload):
```bash
npm run dev
```

For production:
```bash
npm run build
```

### Step 9: Start Development Server

```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Docker Deployment

### Quick Start

```bash
docker-compose up -d
```

### Services

| Service | Port | Description |
|---|---|---|
| nginx | 80, 443 | Web server |
| php | 9000 | PHP-FPM |
| mysql | 3307 | Database |
| redis | 6380 | Cache/Queue |
| queue-worker | - | Background jobs |
| scheduler | - | Cron jobs |

### Docker Commands

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop all services
docker-compose down

# Rebuild containers
docker-compose up -d --build

# Run artisan commands
docker-compose exec php php artisan migrate

# Access MySQL
docker-compose exec mysql mysql -u root -p
```

---

## Production Deployment

### Server Requirements

| Component | Minimum |
|---|---|
| CPU | 2 cores |
| RAM | 4 GB |
| Storage | 20 GB SSD |
| OS | Ubuntu 22.04 LTS |

### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2-fpm php8.2-mysql php8.2-redis php8.2-gd php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath

# Install MySQL 8.0
sudo apt install mysql-server

# Install Redis
sudo apt install redis-server

# Install Nginx
sudo apt install nginx

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 2: Deploy Application

```bash
# Clone repository
cd /var/www
git clone https://github.com/your-org/donatebazaar.git
cd donatebazaar

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/donatebazaar
sudo chmod -R 775 storage bootstrap/cache
```

### Step 3: Configure Nginx

Create `/etc/nginx/sites-available/donatebazaar`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/donatebazaar/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/donatebazaar /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 4: Configure SSL

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### Step 5: Configure Supervisor

```bash
sudo cp /var/www/donatebazaar/supervisor/queue-worker.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

### Step 6: Configure Cron

```bash
sudo crontab -e
```

Add:
```
* * * * * cd /var/www/donatebazaar && php artisan schedule:run >> /dev/null 2>&1
```

### Step 7: Optimize

```bash
cd /var/www/donatebazaar
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## Configuration

### Environment Variables

| Variable | Description | Default |
|---|---|---|
| APP_NAME | Application name | DonateBazaar |
| APP_ENV | Environment | production |
| APP_DEBUG | Debug mode | false |
| APP_URL | Application URL | http://localhost |
| DB_CONNECTION | Database driver | mysql |
| DB_HOST | Database host | 127.0.0.1 |
| DB_PORT | Database port | 3306 |
| DB_DATABASE | Database name | donatebazaar |
| DB_USERNAME | Database username | root |
| DB_PASSWORD | Database password | |
| REDIS_HOST | Redis host | 127.0.0.1 |
| REDIS_PORT | Redis port | 6379 |
| QUEUE_CONNECTION | Queue driver | redis |
| CACHE_DRIVER | Cache driver | redis |
| SESSION_DRIVER | Session driver | redis |
| RAZORPAY_KEY | Razorpay API key | |
| RAZORPAY_SECRET | Razorpay API secret | |
| RAZORPAY_WEBHOOK_SECRET | Webhook secret | |

### Razorpay Configuration

1. Create account at https://razorpay.com
2. Get API keys from Dashboard → Settings → API Keys
3. Set webhook secret in Dashboard → Settings → Webhooks
4. Webhook URL: `https://your-domain.com/payment/webhook`

---

## Queue Workers

### Supervisor Configuration

File: `supervisor/queue-worker.conf`

```ini
[program:fundraise-queue-emails]
command=php artisan queue:work redis --queue=emails --sleep=3 --tries=3 --max-time=3600
directory=/var/www/donatebazaar
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/fundraise-queue-emails.log

[program:fundraise-queue-default]
command=php artisan queue:work redis --queue=default --sleep=3 --tries=3 --max-time=3600
directory=/var/www/donatebazaar
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/fundraise-queue-default.log

[program:fundraise-queue-notifications]
command=php artisan queue:work redis --queue=notifications --sleep=3 --tries=3 --max-time=3600
directory=/var/www/donatebazaar
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/fundraise-queue-notifications.log
```

### Restart Workers

```bash
php artisan queue:restart
```

---

## Cron Jobs

### Scheduled Tasks

| Task | Frequency | Command |
|---|---|---|
| Expire campaigns | Daily | `campaigns:expire` |
| Ending soon notifications | Daily 09:00 | `campaigns:send-ending-soon` |
| KYC reminders | Daily 09:00 | `campaigns:send-kyc-reminders` |
| Prune reservations | Every 5 min | `product-reservations:prune-expired` |
| Release reserves | Daily | `wallet:release-reserves` |

### Crontab Entry

```bash
* * * * * cd /var/www/donatebazaar && php artisan schedule:run >> /dev/null 2>&1
```

---

## Troubleshooting

### Common Issues

#### 500 Internal Server Error

```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check permissions
chmod -R 775 storage bootstrap/cache
```

#### Database Connection Error

```bash
# Verify MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u root -p -e "SELECT 1"

# Check .env credentials
grep DB_ .env
```

#### Queue Not Processing

```bash
# Check supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart all

# Check queue logs
tail -f /var/log/supervisor/fundraise-queue-default.log
```

#### Assets Not Loading

```bash
# Rebuild assets
npm run build

# Check public/build directory
ls -la public/build

# Clear view cache
php artisan view:clear
```

#### Migration Errors

```bash
# Reset and re-run migrations
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

### Getting Help

- Check `storage/logs/laravel.log` for errors
- Run `php artisan about` for system info
- Verify all environment variables are set
