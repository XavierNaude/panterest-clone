FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER = 1

ARG user
ARG uid

COPY ./docker-compose/apache2_config /etc/apache2/sites-enabled
COPY ./ /var/www

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libmagickwand-dev

RUN printf "\n" | pecl install imagick

RUN docker-php-ext-install pdo pdo_mysql zip
RUN docker-php-ext-configure intl
RUN docker-php-ext-enable imagick

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user