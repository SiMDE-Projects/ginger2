FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo_mysql
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --version=2.1.8 --install-dir=/usr/local/bin/ --filename=composer
WORKDIR /var/www/html
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
