FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER = 1

COPY ./project/docker-compose/apache2_config /etc/apache2/sites-enabled
COPY ./project/app /var/www/app
COPY ./project/docker-compose/composer /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev \

RUN printf "\n" | pecl install imagick

RUN docker-php-ext-install pdo pdo_mysql zip
RUN docker-php-ext-configure intl
RUN docker-php-ext-enable imagick


WORKDIR /var/www


