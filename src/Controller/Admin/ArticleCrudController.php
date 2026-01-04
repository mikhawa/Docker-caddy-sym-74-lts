<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
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
            DateTimeField::new('publishAt')->hideOnForm(),
            BooleanField::new('isPublished')->renderAsSwitch()
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Article) return;

        $entityInstance->setCreateAt(new \DateTimeImmutable());
        
        if ($entityInstance->isPublished()) {
            $entityInstance->setPublishAt(new \DateTimeImmutable());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Article) return;

        $entityInstance->setUpdateAt(new \DateTimeImmutable());

        if ($entityInstance->isPublished()) {
            // Si publié et pas de date, on met la date actuelle
            if ($entityInstance->getPublishAt() === null) {
                $entityInstance->setPublishAt(new \DateTimeImmutable());
            }
        } else {
            // Si dépublié, on remet la date à null
            $entityInstance->setPublishAt(null);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
