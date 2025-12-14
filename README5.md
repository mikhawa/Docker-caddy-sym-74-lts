# caddy-sym-74-lts

## Partie 5 Création de l'entité User


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)


## Créons une entité User (Vrai User)

    # ne pas oublier d'entrer dans le conteneur php
    docker compose exec -it php bash
 
    # :/var/www/html$

    php bin/console make:user
    # User
    # Doctrine entity: yes
    # username 
    # hashed password yes


---

[Menu](#menu)

---

### Créons la première migration d'Article

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes


---
[Menu](#menu)
---

### Modifions l'entité User pour ajouter des valeurs par défaut

Celà modifiera les colonnes de la base de données `#[ORM\Column(àjouter les options)]` et les contraintes de validation des formulaires `#[Assert\...]`.

```php
// src/Entity/User.php
# ...
```