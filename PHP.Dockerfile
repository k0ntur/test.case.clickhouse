#базовый образ(image) на базе которого будет создан образ для нашего приложения, берем с https://hub.docker.com/_/php
FROM php:8.2-fpm

#устанавливаем ряд полезных приложений
RUN apt-get update && apt-get -y install  \
    supervisor \
    git  \
    nano \
    zip \
    unzip

#install PHP libs' stage
RUN docker-php-ext-install pdo pdo_mysql sockets

RUN pecl install xdebug && docker-php-ext-enable xdebug

#установим модуль для работы с AMQP
ARG AMPQ_VERSION=2.1.1
RUN apt-get update && apt-get -y install librabbitmq-dev \
    && pecl install amqp-$AMPQ_VERSION \
    && docker-php-ext-enable amqp

#устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#очищаем кеш apt
RUN apt-get clean all
RUN rm -rf /var/lib/apt/lists/*
COPY healthcheck.sh /usr/local/bin/healthcheck
RUN chmod +x /usr/local/bin/healthcheck
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["healthcheck"]

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
EXPOSE 9000
CMD composer install; /usr/bin/supervisord -c /etc/supervisor/supervisord.conf