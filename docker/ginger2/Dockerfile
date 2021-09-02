FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo_mysql
WORKDIR /var/www
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
