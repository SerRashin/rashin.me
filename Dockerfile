FROM php:8.2-fpm AS base

ARG APP_ENV=dev
ENV APP_ENV=$APP_ENV

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    zip \
    unzip \
    ssh \
    g++ \
    git \
    curl \
    libcurl4-gnutls-dev \
    libpq-dev \
    libicu-dev

RUN docker-php-ext-install \
    intl \
    curl \
    bcmath \
    gettext \
    pdo_pgsql


RUN pecl install apcu \
  && docker-php-ext-enable apcu

RUN docker-php-ext-install opcache

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear

RUN docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer


FROM base as dev

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log \
    && chmod 666 /var/log/xdebug.log

WORKDIR /app

CMD php-fpm -F

FROM base as prod

COPY ./ /app

RUN composer install --no-dev --prefer-dist --no-progress --optimize-autoloader
RUN php bin/console cache:clear
