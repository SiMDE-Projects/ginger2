FROM php:8.0-alpine
RUN apk update \
    && apk add  git libzip-dev zip unzip $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-install mysqli pdo_mysql zip \
    && docker-php-ext-enable xdebug
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --version=2.1.8 --install-dir=/usr/local/bin/ --filename=composer
WORKDIR /var/www/html
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
