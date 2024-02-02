FROM php:8.2-fpm
VOLUME /var/www/app

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        ghostscript unzip git  \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng16-16 \
        libpng-dev \
        libcurl4-openssl-dev \
        libgmp-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-install -j$(nproc) iconv mysqli pdo_mysql bcmath curl xml


COPY docker/php/php.ini /usr/local/etc/php/


