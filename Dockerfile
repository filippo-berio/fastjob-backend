FROM php:8.2-fpm as soft

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

# redis
RUN apt-get install libzstd-dev
RUN pecl install -o -f redis; \
    rm -rf /tmp/pear/*
RUN echo 'redis' >> /usr/src/php-available-exts
RUN docker-php-ext-enable redis

# mongodb
RUN apt-get install -y \
        openssl \
        libssl-dev \
        libcurl4-openssl-dev \
    && apt clean \
    && pecl install mongodb \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini



#intl

RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl


RUN docker-php-ext-install opcache

RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        fileinfo

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN echo "memory_limit=4G" >> /usr/local/etc/php/php.ini

COPY ./ /app

RUN composer config extra.symfony.allow-contrib true \
    && composer install -o && rm -rf var/cache/* var/log/* && chmod 777 -R var/*
