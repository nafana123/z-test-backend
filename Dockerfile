FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpq-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
