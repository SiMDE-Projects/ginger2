FROM php:7.4-apache
WORKDIR /var/www/
ADD ./index.php ./public/index.php
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
