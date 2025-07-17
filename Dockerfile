# Etapa 1: Instalación de dependencias PHP y Composer
FROM php:8.2-cli AS composer

WORKDIR /app

# Copiar archivos necesarios para Composer
COPY composer.json composer.lock ./

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
        zip \
        pdo \
        pdo_mysql \
        mbstring \
        gd

# Instalar Composer y ejecutar composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ejecutar composer install separado para depurar mejor
RUN composer install --no-dev --optimize-autoloader --no-interaction || cat /app/composer.json

# Etapa 2: Servidor Apache con PHP
FROM php:8.2-apache

# Habilitar mod_rewrite (útil para Laravel u otros frameworks)
RUN a2enmod rewrite

# Copiar los archivos de la etapa anterior
COPY --from=composer /app /var/www/html

# Opcional: Establecer permisos correctos si lo necesitas
# RUN chown -R www-data:www-data /var/www/html
