# caddy-sym-74-lts

## Partie 3 Création des entités et migrations avec Symfony et Docker

---

## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Vue d'ensemble des entités et relations](#vue-densemble-des-entités-et-relations)
- [Créons une entité Article](#créons-une-entité-article)
  - [Créons la première migration d'Article](#créons-la-première-migration-darticle)
  - [Modifions l'entité Article pour ajouter des valeurs par défaut](#modifions-lentité-article-pour-ajouter-des-valeurs-par-défaut)
  - [Créons la deuxième migration d'Article](#créons-la-deuxième-migration-darticle)

---

## Vue d'ensemble des entités et relations

Voici un aperçu des entités que nous allons créer pour notre application de blog Symfony, ainsi que les relations entre elles.

### Nous allons créer les entités suivantes pour ce blog :
- Catégorie (`Category`)
- Article (`Article`)
- Commentaire (`Comment`)
- Utilisateur (`User`)
- Tag (`Tag`)

### Relations entre les entités :
- Un `Article` peut appartenir à 0, 1 ou toutes les Catégories (ManyToMany)
- Un `Article` peut avoir 0, 1 ou plusieurs Commentaires (OneToMany)
- Un `Article` est écrit par un seul Utilisateur (ManyToOne)
- Un `Article` peut avoir 0, 1 ou plusieurs Tags (ManyToMany)
---
- Un `User` peut écrire plusieurs Articles (OneToMany)
- Un `User` peut écrire plusieurs Commentaires (OneToMany)
--- 
- Un `Tag` peut être associé à 0, 1 ou plusieurs Articles (ManyToMany)
---
- Un `Comment` est écrit par un seul Utilisateur (ManyToOne)
- Un `Comment` appartient à un seul Article (ManyToOne)
--- 
- Une `Category` peut contenir 0, 1 ou plusieurs Articles (ManyToMany)


### Image récapitulative des entités et relations
![Entités et relations](datas/db-schema.png)

---

[Menu](#menu)

---

## Créons une entité Article

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

### Modifions l'entité Article pour ajouter des valeurs par défaut

Celà modifiera les colonnes de la base de données `#[ORM\Column(àjouter les options)]` et les contraintes de validation des formulaires `#[Assert\...]`.

```php

// src/Entity/Article.php

# ...

# utilisation des contraintes de validation
use Symfony\Component\Validator\Constraints as Assert;

# ...

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])] // entier non signé
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide.')]
    #[Assert\Length(
        min: 3,
        max: 150,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 154, unique: true)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    #[Assert\Length(
        min: 7,
        max: 154,
        minMessage: 'Le slug doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le slug ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le texte ne peut pas être vide.')]
    #[Assert\Length(
        min: 20,
        minMessage: 'Le texte doit comporter au moins {{ limit }} caractères.'
    )]
    private ?string $text = null;

    #[ORM\Column(nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true, options: ['default' => null])]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column(nullable: true, options: ['default' => null])]
    private ?\DateTimeImmutable $publishAt = null;

    #[ORM\Column(nullable: true, options: ['default' => false])]
    private ?bool $isPublished = null;
# ...
```

### Créons la deuxième migration d'Article

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes

---

[Menu](#menu)

---

