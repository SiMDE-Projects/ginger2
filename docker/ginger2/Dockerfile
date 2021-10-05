FROM php:7.4-apache
RUN apt update && \
  apt upgrade -y -qq apt-utils libzip-dev zip unzip && \
  apt install -y -qq apt-utils libzip-dev zip unzip && \
  docker-php-ext-install mysqli pdo_mysql zip
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --version=2.1.8 --install-dir=/usr/local/bin/ --filename=composer
WORKDIR /var/www/html
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
