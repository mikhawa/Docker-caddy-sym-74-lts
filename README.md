# caddy-sym-74-lts

### Avec PHP 8.4, MariaDB, PHPMyAdmin, mailpit et Caddy

Sous WSL2 de Windows 11


## Menu
- Installation sous WSL2 de Windows 11
- Création des fichiers Dockerfile, Caddyfile et docker-compose.yml
- Création d'un projet Symfony 7.4 LTS
- Installation de compose.override.yaml pour mailpit et port 3306 MariaDB
- Voir les URL disponibles
- Modification du fichier .env de Symfony
- Accès aux logs des conteneurs
- [Partie 2 Utilisation de Symfony avec Docker](README2.md)
- [Partie 3 Vue d'ensemble des entités et relations](README3.md)
- [Partie 4 Création des entités et migrations avec Symfony et Docker](README4.md)


## Raccourcis utiles

    docker compose up -d --build
    docker compose exec -it php bash

    exit pour sortir (du conteneur) pour git par exemple

## Installation de Docker

Installez `Docker Desktop sous Windows` : 

https://docs.docker.com/desktop/setup/install/windows-install/

Vérifiez que WSL2 est bien activé dans les paramètres de Docker Desktop :

    Paramètres > Ressources > WSL Integration

## Créez Ubuntu sur WSL2

    wsl --install


### User local :

mikhawa

JKuHtgQl4BNp1hJjzxSz

### Ouvrir powerShell et Ubuntu

puis depuis ubuntu

    /home/mikhawa

ou windows :

    \\wsl.localhost\Ubuntu\home\mikhawa\

    mkdir caddy-sym-74-lts
    cd caddy-sym-74-lts

### Création de `Dockerfile`

```Dockerfile
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

```

### Création de `Caddyfile`

```Caddyfile
:80 {
# Racine du projet Symfony
root * /var/www/html/public


# Envoie les requêtes PHP au conteneur PHP-FPM
php_fastcgi php:9000


# Compression Gzip pour la performance
encode gzip


# Servir les fichiers statiques s'ils existent
file_server
}
```

### Création de `docker-compose.yml`

```yaml
services:
  # --- Service PHP Application ---
  php:
    build:
      context: .
      args:
        UID: 1000 # Correspond généralement à l'utilisateur WSL par défaut
    volumes:
      - ./:/var/www/html
    networks:
      - symfony-network

  # --- Service Web Server (Caddy) ---
  caddy:
    image: caddy:2-alpine
    restart: unless-stopped
    ports:
      - "8765:80"
    volumes:
      - ./:/var/www/html
      - ./Caddyfile:/etc/caddy/Caddyfile
      - caddy_data:/data
      - caddy_config:/config
    depends_on:
      - php
    networks:
      - symfony-network

  # --- Service Base de Données (MariaDB) ---
  database:
    image: mariadb:11.4 # Version stable récente
    restart: always
    environment:
      MARIADB_ROOT_PASSWORD: root
      MARIADB_DATABASE: app_db
      MARIADB_USER: app_user
      MARIADB_PASSWORD: app_password
    # Force InnoDB engine (bien que ce soit le défaut, c'est explicite)
    command: --default-storage-engine=innodb
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - symfony-network

  # --- Service Administration BDD (PHPMyAdmin) ---
  pma:
    image: phpmyadmin/phpmyadmin
    restart: always
    ports:
      - "8080:80"
    environment:
      PMA_HOST: database
      UPLOAD_LIMIT: 64M
    depends_on:
      - database
    networks:
      - symfony-network

volumes:
  caddy_data:
  caddy_config:
  db_data:

networks:
  symfony-network:
    driver: bridge

```

Puis :

    docker compose up -d --build

Puis rentrer dans PHP pour travailler :

    docker compose exec -it php bash

    # quittez le conteneur php 
    exit

    # fermer Docker    
    docker compose down

Créer Symfony dans un dossier temporaire :

    composer create-project symfony/skeleton:"7.4.*" tmp_sf

puis

    mv tmp_sf/* .
    mv tmp_sf/.env .
    mv tmp_sf/.gitignore .

puis

    rm -rf tmp_sf

puis

    composer require webapp

    répond non

Puis update

    Le fichier .env

    # DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
    # .env
    DATABASE_URL="mysql://app_user:app_password@database:3306/app_db?serverVersion=11.4-MariaDB&charset=utf8mb4"

Pour fermer:

    docker compose down


## Installation de `compose.override.yaml`

```yaml

services:
  ###> doctrine/doctrine-bundle ###
  database:
    ports:
      # port vers mariaDB pour l'accès externe si nécessaire
      - "3306:3306"
  ###< doctrine/doctrine-bundle ###

  ###> symfony/mailer ###
  mailer:
    image: axllent/mailpit
    ports:
      - "1025"
      - "8025"
    environment:
      MP_SMTP_AUTH_ACCEPT_ANY: 1
      MP_SMTP_AUTH_ALLOW_INSECURE: 1
    networks:
      - symfony-network # Ajoutez cette ligne si le réseau est défini dans docker-compose.yml
###< symfony/mailer ###


```

Puis :

    docker compose down
    docker compose up -d --build

## Voir les URL disponibles

URL :
Symfony : http://localhost:8765/


PHPMyAdmin : http://localhost:8080/ avec app_user
et app_password

Mailpit : http://localhost:54653/

## .env

Modifier dans le fichier `.env` :


```dotenv
# ...

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=
APP_SHARE_DIR=var/share
###< symfony/framework-bundle ###

###> symfony/routing ###
# Configure how to generate URLs in non-HTTP contexts, such as CLI commands.
# See https://symfony.com/doc/current/routing.html#generating-urls-in-commands
DEFAULT_URI=http://localhost
###< symfony/routing ###

###> doctrine/doctrine-bundle ###
# ...
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
# .env
DATABASE_URL="mysql://app_user:app_password@database:3306/app_db?serverVersion=11.4.9-MariaDB-ubu2404&charset=utf8mb4"
###< doctrine/doctrine-bundle ###

### ...

###> symfony/mailer ###
MAILER_DSN=smtp://mailer:1025
###< symfony/mailer ###
```

## Accès aux logs

    # exite pour sortir du conteneur

    docker compose logs -f caddy
    docker compose logs -f php
    docker compose logs -f database
    docker compose logs -f pma
    docker compose logs -f mailer
    docker compose logs -f mailpit
    docker compose logs -f

### Arrêt des conteneurs
    docker compose down
### Redémarrage des conteneurs
    docker compose up -d
### Reconstruction des conteneurs
    docker compose up -d --build
### Accès au conteneur PHP
    docker compose exec -it php bash
### Accès au conteneur Caddy
    docker compose exec -it caddy sh
### Accès au conteneur MariaDB
    docker compose exec -it database bash
### Accès au conteneur PHPMyAdmin
    docker compose exec -it pma bash
### Accès au conteneur Mailpit
    docker compose exec -it mailer sh
### Accès au conteneur Mailpit (ancien nom mailpit)
    docker compose exec -it mailpit sh


[Menu](#Menu)

[Partie 2 Utilisation de Symfony avec Docker](README2.md)

[Partie 3 Vue d'ensemble des entités et relations](README3.md)
[Partie 4 Création des entités et migrations avec Symfony et Docker](README4.md)
