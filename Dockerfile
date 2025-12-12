FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

# Installer dépendances système + outils de build pour pecl
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    acl \
    autoconf \
    build-essential \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    opcache \
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html

# Désactiver short_open_tag et configurer Xdebug (ex: host.docker.internal pour Windows)
RUN printf "short_open_tag=Off\n" > /usr/local/etc/php/conf.d/disable_short_open_tag.ini \
    && printf "xdebug.mode=develop,debug\nxdebug.start_with_request=no\nxdebug.client_host=host.docker.internal\nxdebug.client_port=9003\n" > /usr/local/etc/php/conf.d/20-xdebug.ini

# Création d'un utilisateur non-root pour correspondre à WSL
RUN groupadd -g $GID appgroup && \
    useradd -u $UID -g appgroup -m appuser && \
    chown -R appuser:appgroup /var/www/html

USER appuser
