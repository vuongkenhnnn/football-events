FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron \
    supervisor \
    nano \
    vim \
    tzdata \
    procps

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

WORKDIR /var/www

COPY . /var/www

COPY composer.lock composer.json /var/www/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

RUN mkdir -p /var/www/storage/logs \
    && touch /var/www/storage/logs/laravel.log \
    && touch /var/www/storage/logs/scheduler.log

RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache \
    /var/www/vendor \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache \
    && chmod +x /var/www/artisan

# Setup cron
RUN mkdir -p /var/log/cron \
    && touch /var/log/cron.log \
    && chmod 0644 /var/log/cron.log \
    && echo "* * * * * root cd /var/www && /usr/local/bin/php artisan fetch:football-matches >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel-scheduler \
    && chmod 0644 /etc/cron.d/laravel-scheduler

COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV TZ=Asia/Ho_Chi_Minh

ENTRYPOINT ["/entrypoint.sh"]
