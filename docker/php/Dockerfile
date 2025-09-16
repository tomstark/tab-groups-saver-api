FROM php:8.4-fpm as app

# Useful PHP extension installer image, copy binary into your container
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    apt-get update && \
    # Minimize image size by excluding suggested packages (https://docs.docker.com/build/building/best-practices)
    apt-get install -y --no-install-recommends \
        g++ \
        git \
        libicu-dev \
        libzip-dev \
        zip \
    # Remove apt cache to reduce image size (https://docs.docker.com/build/building/best-practices)
    && rm -rf /var/lib/apt/lists/* \
    && install-php-extensions \
        intl \
        opcache \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        apcu \
    # Install Composer and Symfony CLI
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony \
    # Create required directories and set permissions
    && mkdir -p /var/www/html/api/var \
    && chown -R www-data:www-data /var/www/html/api/var

WORKDIR /var/www/html

# Dev image
# This stage can be targeted as the final build stage for dev use
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target
FROM app as app_dev

# Xdebug has different modes / functionalities. We can default to 'off' and set to 'debug' via an env var if required
ENV XDEBUG_MODE=off

# Copy xdebug config file into container
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install xdebug
RUN install-php-extensions xdebug
