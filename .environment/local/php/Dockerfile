FROM php:8.4-fpm-alpine3.21

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev \
    libzip-dev \
    bash \
    zip

RUN apk add libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install intl

RUN docker-php-ext-install pdo pdo_pgsql
RUN apk add --update linux-headers
RUN apk --update --no-cache add autoconf g++ make && \
    pecl install -f xdebug-3.4.2 && \
    docker-php-ext-enable xdebug && \
    apk del --purge autoconf g++ make

RUN apk --update --no-cache add autoconf g++ make && \
     pecl install -f pcov && \
     docker-php-ext-enable pcov && \
     apk del --purge autoconf g++ make

RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

RUN curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer

WORKDIR /app

CMD ["php-fpm"]
