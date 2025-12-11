FROM php:8.1-apache 

# Instalar extensiones PHP necesarias (MySQLi y cURL, etc.)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    curl \
    unzip \
    libicu-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli curl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo (donde está tu código)
WORKDIR /var/www/html

COPY apache/000-default.conf /etc/apache2/conf-enabled/