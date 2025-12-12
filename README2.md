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
    # Pour exécuter PHP CS Fixer
    ./vendor/bin/php-cs-fixer fix

### Créons un contrôleur

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

### Créons une entité

    php bin/console make:entity Article
    # title:string(150)-notnull
    # slug:string(154)-notnull
    # text:text-notnull
    # createdAt:datetime_immutable-null
    # updatedAt:datetime_immutable-null
    # publishedAt:datetime_immutable-null
    # isPublished:boolean-null
