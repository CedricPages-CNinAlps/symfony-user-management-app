# <span style="color:darkblue">1. Initialisation du projet</span>

## 1.1 Installation de Symfony
<sub>*Git branche "master"*</sub>

```
$ composer create-project symfony/skeleton symfony-user-management-app
$ cd symfony-user-management-app
```
## 1.2 Installation de la webapp
<sub>*Git création de la branche "initialisation-data-base-SQLite" via la commande ```git checkout -b initialisation-data-base-SQLite```*</sub>
```
$ composer require webapp
```
Une fois l'installation réalisée notre .env a évolué avec le reste des dossiers.

## 1.3 Configuration de la base de données (SQLite)
Dans le fichier d'environnement (.env) je vais changer le mode de base de données en SQLite :
```DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"```
en lieu et place de postgresql par défaut à l'installation et initialisation du projet :
```DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"```

Puis je viens créer ma base de données, via la ligne de commande :
```php bin/console doctrine:database:create```
Ce qui génère dans mon dossier "var" le fichier data.db

<sub>*Ajout des fichiers modifiés via ```git add .env README.md etc...```, création d'un commit commenté ```git commit -m Initialisation de la base de données SQLite avec création de celle-ci"```, suivi d'un push ```git push origin initialisation-data-base-SQLite```, d'une création de PullRequest (sous GitHub ou via PhpStorm directement) de la branche "initialisation-data-base-SQLite" sur "master" puis ont fait le merge (sous GitHub ou via PhpStorm directement) des modifications sur "master" et pour finir sur notre projet, on retourne sur notre branche via ```git checkout master``` et nous faisons un ```git pull``` pour mettre à jour notre branche local.*</sub>

**<span style="color:darkred">*Pour le reste des étapes du projet, je ne détaillerai plus la procédure git, je reprendrai uniquement le nom de la branche de développement pour information.*</span>**

# <span style="color:darkblue">2. Création de la Class User</span>
<sub>*Git branche "creation-de-l-entite-user"*</sub>

## 2.1 Génération de la class User
Je viens créer ma class User, via la ligne de commande :
```php bin/console make:entity User```
Ce qui génère dans mon dossier "src" les fichiers suivants :
- src/Entity/User.php
- src/Repository/UserRepository.php

## 2.2 Définition des propriétés de la class User.php
Dans src/Entity/User.php, je viens créer les informations que nous souhaitons utiliser comme :
- Id ;
- Prénom ;
- Nom ;
- Email ;
- Groupe ;
- Date de création ;
- Date de mise à jour.

## 2.3 Génération et exécution de la migration
Pour la dernière étape de la création de la class User, on va générer celle-ci dans notre base de données en faisant la migration, via :

```php bin/console make:migration```

Cela va nous créer le fichier migrations/Version20250212110816.php

```php bin/console doctrine:migrations:migrate```

# <span style="color:darkblue">3. Création du contrôleur API REST et des routes</span>
<sub>*Git branche "creation-api-controller-routes"*</sub>

## 3.1 Création et implémentation du contrôleur
Pour créer notre contrôleur UserController, nous utiliserons la commande :
```php bin/console make:controller ApiUserController```
Cela nous permet de créer les fichiers du contrôleur ainsi que les fichiers de tests pour PHPUnit :
- src/Controller/ApiUserController.php
- templates/api_user/index.html.twig
- tests/Controller/ApiUserControllerTest.php

## 3.2 Création et implémentation du contrôleur
Dans mon contrôler ApiUserController.php, je viens créer mes fonctions de routes en utilisant :
1.	GET /api/users : Récupérer tous les utilisateurs
2.	POST /api/users : Ajouter un utilisateur
3.	PUT /api/users/{id} : Modifier un utilisateur existant
4.	DELETE /api/users/{id} : Supprimer un utilisateur

## 3.3 Test de l'implémentation du contrôleur
Pour vérifier, que tout fonctionne parfaitement, je vais venir démarrer mon server Symfony via :
```symfony server:start```

### 3.3.1 Test GET
Pour la 1ère vérification, nous lançons la commande :
```curl -X GET http://localhost:8000/api/users```
Si nous ne trouvons pas de données, nous aurons le message 'Données incomplètes'. A ce point, aucune données est entrée. 

### 3.3.2 Test POST
Pour créé un utilisateur, nous lançons la commande :
```
curl -X POST http://localhost:8000/api/users \
     -H "Content-Type: application/json" \
     -d '{
           "firstname": "John",
           "lastname": "Doe",
           "email": "john.doe@example.com",
           "groupe": "admin"
         }'
```
Si nous faisons de nouveau la commande ```curl -X GET http://localhost:8000/api/users``` cette fois-ci cela nous retournera la donnée suivante en JSON :  
```
[{"id":1,"lastname":"Doe","firstname":"John","email":"john.doe@example.com","groupe":"admin","createdAt":"2025-02-12T13:44:04+00:00","updatedAt":null,"lastLogin":null}]
```

### 3.3.3 Test PUT
Pour mettre à jour l'utilisateur, nous lançons la commande :
```
curl -X PUT http://localhost:8000/api/users/1 \
     -H "Content-Type: application/json" \
     -d '{
           "lastname": "Doe test update",
         }'
```
Si nous faisons de nouveau la commande : ```curl -X GET http://localhost:8000/api/users``` cette fois-ci cela nous retournera la donnée suivante en JSON, le nom aura été actalisé, ainsi que la date de mise à jour :
```
[{"id":1,"lastname":"Doe test update","firstname":"John","email":"john.doe@example.com","groupe":"admin","createdAt":"2025-02-12T13:44:04+00:00","updatedAt":2025-02-12T13:52:47+00:00,"lastLogin":null}]
```

### 3.3.4 Test DELETE
Pour faire la suppression de l'utilisateur, nous utilisons la commande :
```curl -X DELETE http://localhost:8000/api/users/1```
Et la nous aurons comme retour le message suivant : ```{"message":"Utilisateur supprimé"}```

# <span style="color:darkblue">4. Interface Web</span>
<sub>*Git branche "interface-web"*</sub>
Pour l'interface Web je vais venir utiliser un contrôleur différent pour le rendu et une URL /users...
```php bin/console make:controller UserController```
Cela me crée les fichiers suivants :
- src/Controller/UserController.php 
- templates/user/index.html.twig
- tests/Controller/UserControllerTest.php

Comme précédement, je viens implémenter mon fichier UserController.php avec mes méthodes.

## 4.1 Création d'un formulaire
Pour ce faire, je vais utiliser la commande : ```php bin/console make:form UserType User```
Cela me crée mon fichier "src/Form/UserType.php" dans lequel je vais implémenter mes méthodes au besoin.

## 4.2 Création d'un template twig
Pour ce faire, je vais créer un fichier dans templates/user/edit.html.twig qui me permettra d'appeler mon FORM automatiquement.

## 4.3 Mise en place d'un design Bootstrap
Dans le fichier templates/base.html.twig, on retrouvera les scripts Bootstrap pour un gain de temps.
