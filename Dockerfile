FROM php:7.2-alpine

RUN apk --update add wget \
  curl \
  git \
  build-base \
  libmemcached-dev \
  libmcrypt-dev \
  libxml2-dev \
  zlib-dev \
  autoconf \
  cyrus-sasl-dev \
  libgsasl-dev \
  supervisor

RUN docker-php-ext-install mysqli mbstring pdo pdo_mysql tokenizer xml pcntl

RUN rm /var/cache/apk/*

COPY deployment/supervisord.conf /etc/supervisord.conf

ADD ./deployment/worker.conf /etc/supervisor.d/worker.conf

COPY . /app

RUN mkdir -p /var/log/supervisord

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

WORKDIR /app

