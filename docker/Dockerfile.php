# Dockerfile for running Laravel with PHP 8.2

# source: https://vshloda.medium.com/setting-up-a-laravel-10-development-environment-with-docker-3977a292c8dd

# Set the base image
FROM php:8.2-fpm

# Maintainer
LABEL maintainer="Wahyudi Wibowo <wahyudi.ibo.wibowo@gmail.com>"

# Install dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Laravel
RUN composer install --no-dev --optimize-autoloader

RUN usermod -u 1000 www-data

# Set permissions
RUN chown -R www-data:www-data /var/www/html
