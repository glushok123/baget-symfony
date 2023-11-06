FROM php:8.2-fpm

ARG user_uid
ARG user_gid
RUN usermod -u $user_uid www-data
RUN groupmod -g $user_gid www-data

RUN apt update
RUN apt install autoconf libzip-dev zip unzip \
      libfreetype-dev libpng-dev libicu-dev libturbojpeg-dev --yes
RUN pecl install xdebug
RUN pecl install -o -f redis
RUN docker-php-ext-enable redis
RUN docker-php-ext-install gd
RUN docker-php-ext-enable gd
RUN docker-php-ext-enable xdebug
RUN rm -rf /tmp/pear
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN docker-php-ext-install intl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip


RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./docker/php-fpm/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cash

WORKDIR /app