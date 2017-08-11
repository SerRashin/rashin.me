FROM php:7.1-fpm

COPY . /var/www

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
    libicu-dev \
    postgresql && \
    
    docker-php-ext-install \
    intl \
    curl \
    bcmath \
    gettext \
    mbstring \
    pdo_pgsql && \

    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

RUN composer install --no-interaction
