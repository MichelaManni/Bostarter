FROM php:8.2-apache

# Installa estensione mysqli
RUN docker-php-ext-install mysqli

# Abilita il modulo rewrite di Apache (utile per progetti web)
RUN a2enmod rewrite

# Copia i file del tuo progetto nella root del server Apache
COPY . /var/www/html/