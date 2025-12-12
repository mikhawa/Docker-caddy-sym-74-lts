FROM php:8.4-fpm

# Arguments pour l'utilisateur (UID/GID standard WSL est 1000)
ARG UID=1000
ARG GID=1000

# Installation des dépendances système et des extensions PHP requises pour Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    acl \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    opcache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation de Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Configuration du dossier de travail
WORKDIR /var/www/html

# Création d'un utilisateur système pour mapper celui de WSL2
# Cela permet d'éviter les fichiers créés en "root" impossibles à éditer sous Windows
RUN groupadd -g $GID appgroup && \
    useradd -u $UID -g appgroup -m appuser && \
    chown -R appuser:appgroup /var/www/html

USER appuser