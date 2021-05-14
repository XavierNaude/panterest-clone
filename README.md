Clone de pinterest en suivant le tutoriel des TEACHERS DU NET

## Pré-requis

    *Docker
    *Docker-compose

### Lancer l'environement de développement

```bash
docker-compose up
```

Attendre le chargement des containers Docker et dépendences de Symfony
Quand le message «app_composer exited with code 0» sera visible dans la console vous pourrez ouvrir les liens
-Application : http://localhost:8000/
-PhpMyAdmin: http://localhost:8080/
-MailCatcher: http://localhost:1080/

### Utilisation de l'application

### Premiere utilisation:
-Créer un compte et le valider via le lien reçu dans mailCatcher
-Créer un Pin.

### Ensuite:
-Voir les pins créés.
-Afficher les détails d’un Pin.
-Modifier/Supprimer un Pin que vous avez crée.
-Modifier ses informations.
-Se déconnecter.
-Créer des nouveaux utilisateurs.