FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y     libzip-dev     zip     libicu-dev     && docker-php-ext-install zip intl

# Install redis
RUN pecl install redis
RUN docker-php-ext-enable redis

# Install xdebug
RUN pecl install xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set argument for environment
ARG APP_ENV=development

# Copy appropriate php.ini and enable Xdebug only for development
RUN if [ "$APP_ENV" = "development" ]; then \
    cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini; \
    docker-php-ext-enable xdebug; \
else \
    cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini; \
fi