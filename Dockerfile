FROM php:7.3-apache

RUN mkdir -p /var/www/html/data && \
    chmod -R 777 /var/www/html/data && \
    touch /var/www/html/data/database.sqlite3 && \
    chmod 777 /var/www/html/data/database.sqlite3 && \
    echo 'Deny from all' > /var/www/html/data/.htaccess

COPY config /var/www/html/config
COPY controllers /var/www/html/controllers
COPY css /var/www/html/css
COPY images /var/www/html/images
COPY js /var/www/html/js
COPY models /var/www/html/models
COPY sql /var/www/html/sql
COPY vendor /var/www/html/vendor
COPY views /var/www/html/views
COPY ./*.php /var/www/html/
