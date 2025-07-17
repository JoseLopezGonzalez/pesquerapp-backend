# Etapa 1: Dependencias PHP con Composer (usando PHP 8.2)
FROM php:8.2-cli AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN apt-get update && apt-get install -y unzip git libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install zip pdo pdo_mysql mbstring gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Etapa 2: Apache + PHP
FROM php:8.2-apache

COPY --from=composer /app /var/www/html

# Opcional: Habilitar Apache mod_rewrite
RUN a2enmod rewrite
