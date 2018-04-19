FROM php:7.2.4-fpm-alpine

MAINTAINER lcidral <lcidral@gmail.com>

# Install system packages & PHP extensions required for Yii 2.0 Framework
RUN apk --update add \
        git \
        curl \
        curl-dev \
        bash \
        bash-completion \
        freetype-dev \
        icu \
        ssmtp \
        icu-dev \
        libmcrypt-dev \
        libxml2-dev \
        libintl \
        graphviz \
        libjpeg-turbo-dev \
        libpng-dev \
        mysql-client \
        nodejs && \
    docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql && \
    apk del \
        icu-dev \
        gcc \
        g++ && \
    apk add --no-cache tzdata && \
    set -ex && \
# memcache
    apk add --no-cache --virtual .memcached-deps zlib-dev libmemcached-dev cyrus-sasl-dev && \
    docker-php-source extract && \
    git clone --branch php7 https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/ && \
    docker-php-ext-install memcached && \
    apk add --no-cache --virtual .memcached-runtime-deps libmemcached-libs && \
    apk del .memcached-deps && \
# imagick
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool && \
    export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" && \
    pecl install imagick-3.4.3 && \
    docker-php-ext-enable imagick && \
    apk add --no-cache --virtual .imagick-runtime-deps imagemagick && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    docker-php-source delete && \
    apk del .phpize-deps && \
    rm -r /tmp/pear/*

# xdebug
RUN apk --no-cache add --virtual .build-deps \
        g++ \
        autoconf \
        make && \
    pecl install xdebug-2.6.0 && \
    docker-php-ext-enable xdebug && \
    #apk del .build-deps && \
    rm -r /tmp/pear/*

# Install Composer
ONBUILD ARG GITHUB_OAUTH_TOKEN

ONBUILD RUN set -xe \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer config -g github-oauth.github.com $GITHUB_OAUTH_TOKEN \
    && composer global require "fxp/composer-asset-plugin" \
    && composer global require "hirak/prestissimo"

WORKDIR /var/www/html/

EXPOSE 80
