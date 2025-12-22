# caddy-sym-74-lts

## Partie 6 Créons une connexion pour l'utilisateur


## Menu
- [Partie 1](README.md)
- [Partie 2](README2.md)
- [Partie 3](README3.md)
- [Partie 4](README4.md)
- [Partie 5](README5.md)
- [Créons une connexion pour l'utilisateur admin](#créons-une-connexion-pour-lutilisateur-admin)
- [Ajoutons le lien de connexion dans le template de la barre de navigation](#ajoutons-le-lien-de-connexion-dans-le-template-de-la-barre-de-navigation)]
- [Partie 7 créons les autres entités](README7.md)


## Créons une connexion pour l'utilisateur admin

```bash
    # ne pas oublier d'entrer dans le conteneur php
    docker compose exec -it php bash
    # :/var/www/html$

    php bin/console make:security:form-login
    > SecurityController
    > logout yes
    > test > yes
```


On peut dorénavant accéder à la page de connexion via l'URL :
http://localhost:8765/login

et se connecter avec l'utilisateur :
- username : admin
- password : admin1234

---

[Menu](#menu)

---

## Ajoutons le lien de connexion dans le template de la barre de navigation

Éditons le fichier `templates/base.html.twig` et ajoutez le code suivant dans la barre de navigation (nav) :

```twig
    {% block navbar %}<nav>
            <ul>
                <li><a href="{{ path('app_home') }}">Home</a></li>
                {% if app.user %}
                    <li><a href="{{ path('app_logout') }}">Logout ({{ app.user.username }})</a></li>
                {% else %}
                    <li><a href="{{ path('app_login') }}">Login</a></li>
                {% endif %}
            </ul>
        </nav>     
        {% endblock %}
```

On peut maintenant se connecter et se déconnecter.

---

[Menu](#menu)

---

- [Partie 7 créons les autres entités](README7.md)