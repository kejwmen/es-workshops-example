FROM composer:1.5
FROM php:7.2-alpine

COPY docker/app/php.ini /usr/local/etc/php/php.ini
COPY --from=0 /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app

# Use prestissimo to speed up builds
RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative  --no-interaction
