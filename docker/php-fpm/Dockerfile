FROM php:8.5-fpm-alpine

RUN apk add --no-cache \
    icu-data-full \
    shadow \
    msmtp \
    perl \
    wget \
    procps \
    bash \
    libzip \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    icu \
    zlib \
    imagemagick

RUN apk add --no-cache --virtual .build-deps \
    icu-dev \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    g++ \
    make \
    autoconf \
    automake \
    linux-headers \
    musl-dev && \
    docker-php-ext-configure gd \
        --enable-gd \
        --with-freetype \
        --with-jpeg \
        --with-webp && \
    docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo_mysql \
        intl \
        exif \
        zip && \
    pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug && \
    apk del .build-deps && \
    rm -rf /tmp/*

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

RUN usermod -u 1000 www-data \
    && chown -R www-data:www-data /var/www \
    && chmod 1777 /tmp

USER www-data
WORKDIR /var/www/html