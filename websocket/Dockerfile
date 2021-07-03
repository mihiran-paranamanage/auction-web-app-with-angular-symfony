FROM php:7.4-fpm-alpine
ARG TIMEZONE
COPY ./php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

# PHPIZE
ENV PHPIZE_DEPS \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    pcre-dev \
    make \
    git \
    pkgconf \
    re2c \
    zlib-dev \
    icu-dev
RUN set -xe \
    && apk add --update icu \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

# Module ZMQ
RUN apk add --no-cache --virtual .persistent-deps \
    libsodium-dev \
    zeromq-dev \
    zeromq
RUN git clone https://github.com/mkoppanen/php-zmq /tmp/php-zmq \
    && cd /tmp/php-zmq \
    && phpize  \
    && ./configure  \
    && make  \
    && make install \
    && make test \
    && docker-php-ext-enable zmq

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/websocket
COPY composer*.json ./
COPY WebSocket.php ./
COPY WebSocketServer.php ./
RUN composer update
CMD ["php", "WebSocketServer.php"]

EXPOSE 5000
EXPOSE 5001
