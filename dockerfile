FROM php:8.2-fpm AS builder

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
WORKDIR /var/www
COPY . .

# Instalar dependencias de Laravel (modo producción)
RUN composer install --no-dev --optimize-autoloader

# Compilar assets (si usas Vite)
# RUN npm install && npm run build

# Crear el storage link
RUN php artisan storage:link

# Dar permisos necesarios
RUN chown -R www-data:www-data storage bootstrap/cache

# ------------------------------------------------------
# ETAPA 2: Imagen final con Nginx + PHP-FPM
# ------------------------------------------------------
FROM php:8.2-fpm

# Instalar extensiones y Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml gd

# Copiar configuración de Nginx
COPY ./deploy/nginx.conf /etc/nginx/nginx.conf

# Copiar archivos desde la etapa anterior
COPY --from=builder /var/www /var/www

WORKDIR /var/www

# Exponer el puerto de Nginx
EXPOSE 80

# Iniciar ambos servicios
CMD service nginx start && php-fpm
