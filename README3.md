# caddy-sym-74-lts

## Partie 3 Vue d'ensemble des entités et relations


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Vue d'ensemble des entités et relations](#vue-densemble-des-entités-et-relations)
- [Partie 4](README4.md)




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

[Partie 4](README4.md)
