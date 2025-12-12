# caddy-sym-74-lts

## Partie 2 Utilisation de Symfony avec Docker

## Menu
- [Partie 1](README.md)

## Travaillons dans Symfony

    docker compose up -d --build
    docker compose exec -it php bash

### Exécutons un composer update

    composer update

### Vérifions les exigences de Symfony
    symfony check:req

### Installons PHP CS Fixer

    composer require --dev friendsofphp/php-cs-fixer

### Créons un contrôleur