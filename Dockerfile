FROM php:8.1-fpm as soft

WORKDIR /app

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list

RUN apt update \
    && apt install -y \
    # dev
        symfony-cli \
        wget \
        iputils-ping \
        libzip-dev \
        git \
    # app
        libpq-dev \
        libfreetype6-dev \
        librabbitmq-dev \
    && apt clean

RUN pecl install redis

RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip

RUN docker-php-ext-enable redis

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN echo "memory_limit=4G" >> /usr/local/etc/php/php.ini
COPY ./ /app

RUN composer install -o && rm -rf var/cache/* var/log/* && chmod 777 -R var/*
