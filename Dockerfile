# Etapa 1: Instalar dependencias de PHP
FROM composer:latest AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Etapa 2: Construcción final
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Activar mod_rewrite
RUN a2enmod rewrite

# Copiar código y dependencias
WORKDIR /var/www/html

COPY . .

COPY --from=vendor /app/vendor ./vendor

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Puerto expuesto por Apache
EXPOSE 80
