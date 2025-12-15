# caddy-sym-74-lts

## Partie 5 Création de l'entité User


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Créons une entité User (Vrai User)](#créons-une-entité-user-vrai-user)
- [Créons la première migration de User](#créons-la-première-migration-de-user)
- [Modifions l'entité User pour ajouter des valeurs par défaut](#modifions-lentité-user-pour-ajouter-des-valeurs-par-défaut)
- [Créons le lien entre User et Article (OneToMany)](#créons-le-lien-entre-user-et-article-onetomany)
- [Créons la migration de l'entité User](#créons-la-migration-de-lentité-user)
- [Installons le composant de gestion des fixtures](#installons-le-composant-de-gestion-des-fixtures)
- [Créons une fixture pour l'entité User](#créons-une-fixture-pour-lentité-user)
- [Chargeons-les fixtures dans la base de données](#chargeons-les-fixtures-dans-la-base-de-données)
- [Partie 6](README6.md)


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

### Créons la première migration de User
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

### Créons une fixture pour l'entité User

    php bin/console make:fixture UserFixture

Modifions le fichier `src/DataFixtures/UserFixture.php` pour ajouter un seul utilisateur `admin` avec un mot de passe haché pour le moment.

User:

Admin

PWD: admin1234

```php
<?php

// src/DataFixtures/UserFixture.php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur admin
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setEmail('michaeljpitz@gmail.com');
        $adminUser->setUniqID(uniqid('uq_',true));
        $adminUser->setStatus(1); // statut actif
        $adminUser->setDateInscription(new \DateTimeImmutable());
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'admin1234');
        $adminUser->setPassword($hashedPassword);
        $manager->persist($adminUser);
        $manager->flush();
    }
}
```
### Chargeons-les fixtures dans la base de données

    php bin/console doctrine:fixtures:load # > yes

---
[Menu](#menu)
---
[Partie 6](README6.md)