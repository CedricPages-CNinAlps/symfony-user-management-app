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
