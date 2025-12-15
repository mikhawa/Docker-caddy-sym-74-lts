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

```bash
    php bin/console make:entity User
    # ajouter les champs suivants :
    # password:string(255)-notnull
    #  email:string(200)-notnull
    #  uniqID:string(255)-notnull
    # status:smallint-notnull
    # dateInscription:datetime_immutable-null
```

### Créons le lien entre User et Article (OneToMany)

```bash
 New property name (press <return> to stop adding fields):
 > articles

 Field type (enter ? to see all types) [string]:
 > OneToMany

 What class should this entity be related to?:
 > Article

 A new property will also be added to the Article class so that you can access and set the related User object from it.

 New field name inside Article [user]:
 > 

 Is the Article.user property allowed to be null (nullable)? (yes/no) [yes]:
 > 

 updated: src/Entity/User.php
 updated: src/Entity/Article.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
```

Le fichier `config/packages/security.yaml` modifié automatiquement pour utiliser l'entité User.
```yaml
security:
    # https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords
    password_hashers:
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'

    # https://symfony.com/doc/current/security.html#loading-the-user-the-user-provider
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_user_provider:
            entity:
                class: App\Entity\User
                property: username
# ...
```
---

[Menu](#menu)

---

### Créons la migration de l'entité User

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes
---
[Menu](#menu)   
---

### Installons le composant de gestion des fixtures

    composer require orm-fixtures --dev 



```php
// src/DataFixtures/AppFixtures.php
# ...
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
# ...