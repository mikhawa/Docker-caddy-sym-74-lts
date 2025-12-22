# caddy-sym-74-lts

## Partie 7 créons les autres entités


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Partie 5](README5.md)
- [Partie 6](README6.md)
- [Créons une entité Comment](#créons-une-entité-comment)
- [Créons la migration de l'entité Comment](#créons-la-migration-de-lentité-comment)
- [Créons une entité Tag](#créons-une-entité-tag)
- [Créons la migration de l'entité Tag](#créons-la-migration-de-lentité-tag)
- [Partie 8](README8.md)

**Les modifications des entités se font toujours après la création**

## Créons une entité Comment


```bash
    appuser@68e8c6a4f64f:/var/www/html$ php bin/console make:entity Comment
 created: src/Entity/Comment.php
 created: src/Repository/CommentRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > text

 Field type (enter ? to see all types) [string]:
 > 2500


 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 2500

 Can this field be null in the database (nullable) (yes/no) [no]:
 >  no

 updated: src/Entity/Comment.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > createAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Comment.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > publishAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Comment.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > isPublished

 Field type (enter ? to see all types) [boolean]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes 

 updated: src/Entity/Comment.php

 
 Add another property? Enter the property name (or press <return> to stop adding fields):
 > article



 Field type (enter ? to see all types) [string]:
 > ManyToOne

 What class should this entity be related to?:
 > Article

 Is the Comment.article property allowed to be null (nullable)? (yes/no) [yes]:
 > 

 Do you want to add a new property to Article so that you can access/update Comment objects from it - e.g. $article->getComments()? (yes/no) [yes]:
 > 

 A new property will also be added to the Article class so that you can access the related Comment objects from it.

 New field name inside Article [comments]:
 > 

 updated: src/Entity/Comment.php
 updated: src/Entity/Article.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > User

 Field type (enter ? to see all types) [string]:
 > ManyToOne

 What class should this entity be related to?:
 > 
appuser@68e8c6a4f64f:/var/www/html$ php bin/console make:entity Comment
 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > user

 Field type (enter ? to see all types) [string]:
 > ManyToOne

 What class should this entity be related to?:
 > User

 Is the Comment.user property allowed to be null (nullable)? (yes/no) [yes]:
 > 

 Do you want to add a new property to User so that you can access/update Comment objects from it - e.g. $user->getComments()? (yes/no) [yes]:
 > 

 A new property will also be added to the User class so that you can access the related Comment objects from it.

 New field name inside User [comments]:
 > 

 updated: src/Entity/Comment.php
 updated: src/Entity/User.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 

           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration

```

## Créons la migration de l'entité Comment
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes
---
[Menu](#menu)
---

## Créons une entité Tag

```bash
appuser@1ec4508cc2ba:/var/www/html$ php bin/console make:entity Tag
 created: src/Entity/Tag.php
 created: src/Repository/TagRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > title
  

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 

 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > slug

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 105

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > articles

 Field type (enter ? to see all types) [string]:
 > ManyToMany

 What class should this entity be related to?:
 > Article

 Do you want to add a new property to Article so that you can access/update Tag objects from it - e.g. $article->getTags()? (yes/no) [yes]:
 > 

 A new property will also be added to the Article class so that you can access the related Tag objects from it.

 New field name inside Article [tags]:
 > 

 updated: src/Entity/Tag.php
 updated: src/Entity/Article.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 


           
  Success! 
           
```

## Créons la migration de l'entité Tag
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate # > yes
---
[Menu](#menu)
---