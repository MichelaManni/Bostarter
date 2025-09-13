FROM php:8.2-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y --no-install-recommends \
libssl-dev pkg-config autoconf make g++ ca-certificates \
 && pecl install mongodb \
 && docker-php-ext-enable mongodb \
 && apt-get purge -y --auto-remove autoconf make g++ pkg-config libssl-dev \
 && rm -rf /var/lib/apt/lists/*
RUN a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
