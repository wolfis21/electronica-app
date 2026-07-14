# ==========================================
# Etapa 1: Construcción del Frontend (Node)
# ==========================================
FROM node:22-alpine as frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
RUN npm run build

# ==========================================
# Etapa 2: Dependencias del Backend (Composer)
# ==========================================
FROM composer:2.7 as backend
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs

# ==========================================
# Etapa 3: Imagen Final de Producción
# ==========================================
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache para las URLs amigables de Laravel
RUN a2enmod rewrite

# Cambiar el DocumentRoot de Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Copiar la carpeta vendor desde la Etapa 2 (Backend)
COPY --from=backend /app/vendor/ /var/www/html/vendor/

# Copiar los assets compilados desde la Etapa 1 (Frontend)
COPY --from=frontend /app/public/build/ /var/www/html/public/build/

# Configurar los permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80