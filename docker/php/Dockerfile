FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    zip \
    exif \
    pcntl \
    opcache

# Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize

RUN npm ci --omit=dev && npm run build

RUN php artisan optimize

RUN php artisan view:cache && php artisan route:cache

RUN php -r "
    \$env = file_get_contents('.env.example');
    \$env = str_replace('APP_ENV=local', 'APP_ENV=production', \$env);
    \$env = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', \$env);
    file_put_contents('.env', \$env);
"

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

# ─────────────────────────────────────────────
FROM base AS dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/www/storage/logs/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --no-progress

RUN npm install
