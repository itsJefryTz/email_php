FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN composer install --no-interaction --no-plugins --no-scripts --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

EXPOSE 80