FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libonig-dev zip libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
