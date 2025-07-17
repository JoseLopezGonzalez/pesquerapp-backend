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

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar el resto del código para ejecutar composer install (en la raíz del proyecto)
COPY . .

# Ejecutar composer install separado (permite ver errores si falla)
RUN composer install --no-dev --optimize-autoloader --no-interaction || cat /app/composer.json

---

# Etapa 2: Servidor Apache con PHP
FROM php:8.2-apache

# Habilitar mod_rewrite (útil para Laravel u otros frameworks)
RUN a2enmod rewrite

# Configurar Apache para que apunte a /public si es Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copiar el proyecto desde la etapa anterior
COPY --from=composer /app /var/www/html

# Establecer permisos si hace falta (opcional)
# RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
