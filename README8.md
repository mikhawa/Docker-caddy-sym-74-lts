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
    #...
    main:
    lazy: true
    provider: app_user_provider
    form_login:
      login_path: app_login
      check_path: app_login
      enable_csrf: true
      # on redirige vers /admin après login
      default_target_path: admin
    logout:
      path: app_logout
      # where to redirect after logout
      # target: app_any_route
    # Note: Only the *first* matching rule is applied
    access_control:
         - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
```

Créons le dashboard en twig:

```twig
{# templates/admin/dashboard.html.twig #}
{% extends '@EasyAdmin/page/content.html.twig' %}
```

Puis faisons en sorte que notre DashboardController utilise ce template: `src/Controller/Admin/DashboardController.php`

## The CSRF token is invalid. Please try to resubmit the form.

Création d'un APP_SECRET dans le fichier .env.local

```env
php -r 'echo bin2hex(random_bytes(32));'
# ou 
openssl rand -hex 32
```

Le problème venait de `config/packages/csrf.yaml` en conflit avec EasyAdminBundle

```yaml
# config/packages/csrf.yaml
# Enable stateless CSRF protection for forms and logins/logouts
framework:
    form:
        csrf_protection:
            # token_id: submit # Commenté pour laisser Symfony gérer les IDs par défaut

    csrf_protection:
        # stateless_token_ids: # Commenté pour revenir au stockage en session standard
        #    - submit
        #    - authenticate
        #    - logout
```

## Lien utiles:
- https://symfony.com/bundles/EasyAdminBundle/current/index.html