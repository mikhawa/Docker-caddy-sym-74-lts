# caddy-sym-74-lts

## Partie 6 Créons une connexion pour l'utilisateur


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Partie 5](README5.md)


## Créons une connexion pour l'utilisateur
```bash
    # ne pas oublier d'entrer dans le conteneur php
    docker compose exec -it php bash
    # :/var/www/html$

    php bin/console make:security:form-login
    > SecurityController
    > logout yes
    > test > yes