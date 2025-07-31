# docker/php/Dockerfile
FROM php:8.1-fpm

# Instalacja wymaganych rozszerzeń PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql pcntl

# Install dependencies
RUN apt-get update && apt-get install -y     libzip-dev     zip     libicu-dev     && docker-php-ext-install zip intl

# Install redis
RUN pecl install redis
RUN docker-php-ext-enable redis


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy configuration files
COPY php.ini-development /tmp/php.ini-development
COPY php.ini-production /tmp/php.ini-production

# Set argument for environment with default value
ARG CI_ENVIRONMENT=${CI_ENVIRONMENT:-production}

# Set environment variable
ENV CI_ENVIRONMENT=${CI_ENVIRONMENT:-production}

# Set working directory
WORKDIR /var/www/html

# Define target configuration directory
ENV PHP_CONFIG_DIR=/usr/local/etc/php

# Copy appropriate php.ini based on environment
RUN if [ "$CI_ENVIRONMENT" = "development" ] ; then \
        cp /tmp/php.ini-development ${PHP_CONFIG_DIR}/php.ini ; \
    else \
        cp /tmp/php.ini-production ${PHP_CONFIG_DIR}/php.ini ; \
    fi && \
    rm -f /tmp/php.ini-*