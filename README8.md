# caddy-sym-74-lts

## Partie 8 Installation d'EasyAdminBundle et création du dashboard


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Partie 5](README5.md)
- [Partie 6](README6.md)
- [Créons une entité Comment](#créons-une-entité-comment)
- [Créons la migration de l'entité Comment](#créons-la-migration-de-lentité-comment)
- [Créons une entité Tag](#créons-une-entité-tag)
- [Créons la migration de l'entité Tag](#créons-la-migration-de-lentité-tag)
- [Partie 8](README8.md)

## Installation d'EasyAdminBundle et création du dashboard

Documentation officielle :

https://symfony.com/bundles/EasyAdminBundle/current/index.html

    # ne pas oublier d'entrer dans le conteneur php
    docker compose exec -it php bash

    # :/var/www/html$

    composer require easycorp/easyadmin-bundle
    php bin/console make:admin:dashboard
    # DashboardController
    php bin/console make:admin:crud
    # Quelle entité voulez-vous gérer avec EasyAdmin? Article
    # ArticleCrudController

### Vidons le cache et regardons les routes

```bash
    php bin/console cache:clear
    php bin/console debug:router
```

### Accédons à l'interface d'administration en tant qu'administrateur uniquement !

En modifiant `config/packages/security.yaml`

```yaml
    # Note: Only the *first* matching rule is applied
    access_control:
         - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
```