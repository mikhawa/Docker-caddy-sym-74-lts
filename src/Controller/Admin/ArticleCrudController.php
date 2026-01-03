<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            # caché sur les formulaires
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            # Transforme le titre en slug
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('text'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('publishAt'),
            BooleanField::new('isPublished')
        ];
    }

}
