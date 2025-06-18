# project-root/php/Dockerfile
FROM php:8.2-apache

# Evitiamo cache di apt e installiamo le estensioni MySQLi e PDO_MySQL
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
 && docker-php-ext-install mysqli pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# (Opzionale) Abilita mod_rewrite, se ti serve per il routing
RUN a2enmod rewrite

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Copia tutti i file PHP nella webroot
COPY . /var/www/html

# Assicura permessi corretti
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
