# caddy-sym-74-lts

## Partie 4 Création des entités et migrations avec Symfony et Docker


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Créons une entité Article](#créons-une-entité-article)
  - [Créons la première migration d'Article](#créons-la-première-migration-darticle)
  - [Modifions l'entité Article pour ajouter des valeurs par défaut](#modifions-lentité-article-pour-ajouter-des-valeurs-par-défaut)
  - [Créons la deuxième migration d'Article](#créons-la-deuxième-migration-darticle)

- [Créons une entité Category](#créons-une-entité-category)
  - [Créons la première migration de Category](#créons-la-première-migration-de-category)
  - [Modifions l'entité Category pour ajouter des valeurs par défaut](#modifions-lentité-category-pour-ajouter-des-valeurs-par-défaut)
  - [Créons la deuxième migration de Category](#créons-la-deuxième-migration-de-category)



## Créons une entité Article

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

## Créons une entité Category

    php bin/console make:entity Category
    # title:string(100)-notnull
    # slug:string(104)-notnull
    # description:string(600)-null
    # level:integer-null

---

[Menu](#menu)

---

### Créons la première migration de Category

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes

### Modifions l'entité Category pour ajouter des valeurs par défaut

Celà modifiera les colonnes de la base de données `#[ORM\Column(àjouter les options)]` et les contraintes de validation des formulaires `#[Assert\...]`.

```php
<?php

// src/Entity/Category.php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
// utilisation des contraintes de validation
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 104, unique: true)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide')]
    #[Assert\Length(
        min: 2,
        max: 104,
        minMessage: 'Le slug doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le slug ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $slug = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true, options: ['default' => 0, 'unsigned' => true])]
    private ?int $level = null;

# ...
```

### Créons la deuxième migration de Category

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes

---

[Menu](#menu)

---

## Jointure des entités Article et Category (ManyToMany)
    php bin/console make:entity Article
    # categories: ManyToMany
    # What class should this entity be related to: Category
    # Do you want to add a new property to Category so that you can access/update Article objects from it - e.g. $category->getArticles()? (yes/no) yes
    #  A new property will also be added to the Category class so that you can access the related Article objects from it [articles]

### Les jointures ManyToMany créent automatiquement une table de jointure dans la base de données. Ainsi que des méthodes d'ajout et de suppression dans les entités.

#### Article.php

```php
// src/Entity/Article.php
# ...
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
# ...

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

        /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
# ...
```
#### Category.php

```php
// src/Entity/Category.php
# ...
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
# ... 
    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'categories')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }
# ...
```
### Créons la migration pour la jointure ManyToMany

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes
---
[Menu](#menu)
---