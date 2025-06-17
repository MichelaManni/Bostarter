FROM php:8.2-apache  

RUN docker-php-ext-install mysqli && \
    a2enmod rewrite && \
    service apache2 restart

ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf