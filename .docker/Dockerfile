FROM php:8.4-apache

ARG NODE_VERSION=18
ENV WWWGROUP=1000
ENV WWWUSER=1000
ARG ENTRYPOINT

# Install packages
RUN apt-get update && apt-get install -y \
    build-essential \
    nano \
    git \
    zip \
    curl \
    cron \
    sudo \
    unzip \
    libgd-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libxml2-dev \
    libmagickwand-dev \
    supervisor \
    g++

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

# Common PHP Extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    gd \
    exif \
    soap \
    sockets

RUN pecl install --onlyreqdeps --force redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

# Set a volume mount point for your code
VOLUME /var/www/html

# Copy code and run composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/tmp
RUN cd /var/www/tmp && composer install --no-dev
RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt-get install -y nodejs
RUN cd /var/www/tmp && npm install -g npm
RUN cd /var/www/tmp && npm install
RUN cd /var/www/tmp && npm run build

RUN cp -R /var/www/tmp/. /var/www/html/

RUN chown -R www-data:www-data /var/www/html/temp /var/www/html/log && \
    chmod -R 775 /var/www/html/temp /var/www/html/log && \
    chmod +x /var/www/html/bin/console

WORKDIR /var/www/html

RUN groupadd --force -g $WWWGROUP main
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER main

#COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/php.ini /usr/local/etc/php/conf.d/local.ini

EXPOSE 80

# Ensure the entrypoint file can be run
RUN chmod +x /var/www/tmp/.docker/docker-entrypoint.sh
