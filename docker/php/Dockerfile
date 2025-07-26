

FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y     libzip-dev     zip     libicu-dev     && docker-php-ext-install zip intl


# Install redis
RUN pecl install redis
RUN docker-php-ext-enable redis

# Install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Configure xdebug
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

