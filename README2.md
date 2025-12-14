# caddy-sym-74-lts

## Partie 2 Utilisation de Symfony avec Docker


## Menu
- [Partie 1](README.md)
- [Travaillons dans Symfony](#travaillons-dans-symfony)
- [Voir les urls disponibles](#voir-les-urls-disponibles)
  - [Exécutons un composer update](#exécutons-un-composer-update)
  - [Vérifions les exigences de Symfony](#vérifions-les-exigences-de-symfony)
- [Installons PHP CS Fixer](#installons-php-cs-fixer)
- [Créons un contrôleur](#créons-un-contrôleur)
  - [Modifions le test](#modifions-le-test)
  - [Exécutons les tests](#exécutons-les-tests)
- [Partie 3](README3.md)


## Travaillons dans Symfony

    docker compose up -d --build
    docker compose exec -it php bash

    # quittez le conteneur php avec exit pour git par exemple
    exit

    # Fermeture de tous les conteneurs
    docker compose down

---

[Menu](#menu)

---

## Voir les URLs disponibles

    Symfony : 

http://localhost:8765/

    PHPMyAdmin : 

http://localhost:8080/

    Mailpit :

http://localhost:54653/

---

[Menu](#menu)

---

### Exécutons un composer update

    composer update

### Vérifions les exigences de Symfony
    symfony check:req

## Installons PHP CS Fixer

    composer require --dev friendsofphp/php-cs-fixer
    # Pour exécuter PHP CS Fixer
    ./vendor/bin/php-cs-fixer fix

---

[Menu](#menu)

---

## Créons un contrôleur

    php bin/console make:controller MainController
    # > yes pour les tests

Utilisons-le comme page d'accueil
```php

// src/Controller/MainController.php
# ...
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
# ...
```
### Modifions le test

```php

// tests/Controller/MainControllerTest.php

    # ...
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
    }
    # ...
```

### Exécutons les tests

    php bin/phpunit

---

[Menu](#menu)

---

[Partie 3 Vue d'ensemble des entités et relations](README3.md)
[Partie 4 Création des entités et migrations avec Symfony et Docker](README4.md)

