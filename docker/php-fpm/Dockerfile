FROM php:7.4.33-fpm-alpine3.16

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
# RUN apt-get update \
#     && apt-get -y --no-install-recommends install  php7.4-mysql \
#     && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

WORKDIR /application
