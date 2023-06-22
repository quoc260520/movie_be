FROM php:8.1-fpm

# set main params
ENV APP_HOME=/var/www/html

# set working directory
WORKDIR $APP_HOME

# Cài đặt các dependencies hệ thống
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libmemcached-dev \
    libmagickwand-dev \
    libssl-dev \
    librabbitmq-dev \
    libssh2-1-dev \
    curl \
    nano \
    git \
    libmcrypt-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libxslt-dev \
    libwebp-dev \
    libxpm-dev \
    libgmp-dev \
    libbz2-dev \
    libldap2-dev \
    libsqlite3-dev \
    libzip-dev \
    librabbitmq-dev

# Cài đặt các extensions PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    gd \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache \
    intl \
    ldap \
    sockets

#install mysqli, socket for push cronjob
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# create document root
RUN mkdir -p $APP_HOME/public

# change owner
RUN chown -R www-data:www-data $APP_HOME

# put php config for Laravel
COPY ./docker/config/php.ini /usr/local/etc/php/php.ini

# install Xdebug in case development environment
# COPY ./docker/other/do_we_need_xdebug.sh /tmp/
# COPY ./docker/dev/xdebug.ini /tmp/
# RUN chmod u+x /tmp/do_we_need_xdebug.sh && /tmp/do_we_need_xdebug.sh

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer --version=1.10.17

# install npm
RUN curl -sL https://deb.nodesource.com/setup_12.x  | bash -
RUN apt-get -y install nodejs


# create composer folder for user www-data
RUN mkdir -p /var/www/.composer && chown -R www-data:www-data /var/www/.composer

#chomd logs
RUN chmod -R 777 storage

EXPOSE 9000

USER www-data

# copy source files and config files
# COPY --chown=www-data:www-data . $APP_HOME/
# COPY --chown=www-data:www-data .env.$ENV $APP_HOME/.env

# install all PHP dependencies
# RUN if [ "$BUILD_ARGUMENT_ENV" = "dev" ] || [ "$BUILD_ARGUMENT_ENV" = "test" ]; then composer install --no-interaction --no-progress; \
#     else composer install --no-interaction --no-progress --no-dev; \
#     fi

USER root
