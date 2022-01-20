FROM php:8-fpm-alpine

ARG UID
ARG GID

RUN apk update && apk upgrade \
    && apk add --no-cache bash

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir /.composer
RUN chmod -R 0777 /.composer
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN addgroup -g ${UID} hillel && \
    adduser -D -u ${GID} -G hillel hillel && \
    mkdir -p /home/hillel/.ssh && \
    chmod 600 -R /home/hillel/.ssh && \
    chown -R hillel:hillel /home/hillel/.ssh

USER hillel