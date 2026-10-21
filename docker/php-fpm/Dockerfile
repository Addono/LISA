FROM composer:2 AS composer

FROM php:8.5-fpm-alpine

RUN apk add --no-cache icu-libs oniguruma \
	&& apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev oniguruma-dev \
	&& docker-php-ext-install intl mbstring mysqli \
	&& apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /application

CMD ["sh", "-c", "composer install --no-interaction --prefer-dist && mkdir -p writable/cache writable/logs writable/session writable/uploads && chown -R www-data:www-data writable && exec php-fpm"]
