# caddy-sym-74-lts

## Partie 5 Création de l'enitité User


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)


## Créons une entité User

    # ne pas oublier d'entrer dans le conteneur php
    docker compose exec -it php bash
 
    # :/var/www/html$

    php bin/console make:entity Article
    # title:string(150)-notnull
    # slug:string(154)-notnull
    # text:text-notnull
    # createdAt:datetime_immutable-null
    # updatedAt:datetime_immutable-null
    # publishedAt:datetime_immutable-null
    # isPublished:boolean-null

---

[Menu](#menu)

---

### Créons la première migration d'Article

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes


---
[Menu](#menu)
---