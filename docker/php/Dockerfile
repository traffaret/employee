FROM php:7.4-cli

LABEL maintainer="Oleg Tikhonov <to@toro.one>"

RUN apt-get update && apt-get install -y \
        git \
        zip \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    rm -rf /tmp/pear

# PHP configuration

COPY ./docker/php/php-development.ini "$PHP_INI_DIR"/php.ini
COPY ./docker/php/conf.d "$PHP_INI_DIR"/conf.d

RUN curl -fsSL https://getcomposer.org/installer | php -- --2 --install-dir=/usr/local/bin --filename=composer

# Application directory

WORKDIR /usr/app

RUN mkdir -p ./profiler

COPY . .

EXPOSE 8000

RUN composer check-platform-reqs
