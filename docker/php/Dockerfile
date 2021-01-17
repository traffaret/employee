FROM php:7.4-zts

LABEL maintainer="Oleg Tikhonov <to@toro.one>"

# ICU version to install

ARG icu="67.1"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apt-get update && apt-get install -y \
    git \
    openssh-client \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# ICU and PHP intl extension

RUN apt-get update && apt-get install -y \
    python3 \
    && ICU_SRC=$(echo $icu | sed -e 's/\./_/') \
    && ICU_RELEASE=$(echo $icu | sed -e 's/\./-/') \
    && cd \
    && curl -fsSL "https://github.com/unicode-org/icu/releases/download/release-${ICU_RELEASE}/icu4c-${ICU_SRC}-src.tgz" -o icu.tar.gz \
    && tar -zxf icu.tar.gz \
    && cd icu/source \
    && ./configure --prefix="/opt/icu" && make && make install \
    && cd && rm -rf icu && rm -f icu.tar.gz \
    && LD_LIBRARY_PATH=/opt/icu/lib PKG_CONFIG_PATH=/opt/icu/lib/pkgconfig docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get remove --purge python3 -y && apt-get autoremove -y && rm -rf /usr/lib/python3* && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP extensions

RUN install-php-extensions \
    bcmath \
    gmp \
    parallel \
    sockets \
    timezonedb \
    zip \
    xdebug

# PHP configuration

COPY ./docker/php/php-development.ini "$PHP_INI_DIR"/php.ini
COPY ./docker/php/conf.d "$PHP_INI_DIR"/conf.d

# Application directory

WORKDIR /usr/app

# Composer

COPY composer.json composer.lock ./
RUN composer check-platform-reqs && composer install

RUN mkdir -p ./profiler

COPY . .

EXPOSE 9060

CMD ["tail", "-f", "/dev/null"]
