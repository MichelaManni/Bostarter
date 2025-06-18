FROM php:8.2-apache

# Installa estensioni necessarie
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita mod_rewrite (se ti serve per .htaccess)
RUN a2enmod rewrite

# Copia il codice nella cartella di Apache
COPY . /var/www/html/

# Imposta i permessi se necessario
RUN chown -R www-data:www-data /var/www/html
