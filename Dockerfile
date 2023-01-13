FROM php:8.1-fpm

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
#RUN no | pecl install -o -f redis; \
RUN pecl install -o -f redis; \
    rm -rf /tmp/pear/*
RUN echo 'redis' >> /usr/src/php-available-exts
RUN docker-php-ext-enable redis

RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN echo "memory_limit=4G" >> /usr/local/etc/php/php.ini
COPY ./ /app

RUN composer install -o && rm -rf var/cache/* var/log/* && chmod 777 -R var/*
