# caddy-sym-74-lts

## Partie 9 Création des interfaces d'administration avec EasyAdminBundle : Article


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Partie 5](README5.md)
- [Partie 6](README6.md)
- [Partie 7](README7.md)
- [Partie 8](README8.md)
- [ArticleCrudController](#articlecrudcontroller)
- [Article.php](#articlephp)


### Création des interfaces d'administration avec EasyAdminBundle

Documentation officielle :

Accueil : 
- https://symfony.com/bundles/EasyAdminBundle/current/index.html

CRUD :
- https://symfony.com/bundles/EasyAdminBundle/current/crud.html

Les champs :
- https://symfony.com/bundles/EasyAdminBundle/current/fields.html

### ArticleCrudController

On va mettre en place un slug automatique, cacher certains champs et activer la date de publication en cas de publication/dépublication.

    src/Controller/Admin/ArticleCrudController.php

```php
<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // https://symfony.com/bundles/EasyAdminBundle/current/fields.html#field-types
        return [
            // caché sur les formulaires
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            // Transforme le titre en slug
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('text'),
            // Correction du nom du champ: createAt au lieu de createdAt
            DateTimeField::new('createAt')->hideOnForm(),
            DateTimeField::new('updateAt')->hideOnForm(),
            // On cache publishAt du formulaire car il est géré automatiquement
            DateTimeField::new('publishAt'),
            BooleanField::new('isPublished')->renderAsSwitch()
        ];
    }
}


```

[Menu](#Menu)

### Article.php

    src/Entity/Article.php

Des modifications sont également notées dans Article, pour la mise à jour de la date de publication lorsqu'on publie ou dépublie un article.

```php
<?php

// src/Entity/Article.php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// utilisation des contraintes de validation
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article
{
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

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article')]
    private Collection $comments;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'articles')]
    private Collection $tags;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createAt = new \DateTimeImmutable();
        
        if ($this->isPublished) {
            $this->publishAt = new \DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updateAt = new \DateTimeImmutable();

        if ($this->isPublished) {
            if ($this->publishAt === null) {
                $this->publishAt = new \DateTimeImmutable();
            }
        } else {
            $this->publishAt = null;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getPublishAt(): ?\DateTimeImmutable
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeImmutable $publishAt): static
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addArticle($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeArticle($this);
        }

        return $this;
    }
}

```

[Menu](#Menu)
