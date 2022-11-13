# Pépettes

Un endroit pour partager toutes tes meilleurs adresses avec quelques critères en plus, notamment : 
* Le taux de tranquilité 
* Le taux de charo

## Pourquoi Pépettes ? 

Pour que les filles qui veulent sortir sachent où elles vont mettre les pieds. Selon leur humeur, elles pourront décider de l'endroit idéal pour faire passer le temps. 

<img width="1440" alt="image" src="https://user-images.githubusercontent.com/47384185/201544786-ed71919d-6161-43d2-84e7-eccc0a99add0.png">

## Pré-requis 

Le projet utilise Symfony et Yarn. Voici les versions utilisées : 
* symfony@6.1
* php@8.1
* node@18.10

## Installation des bundles nécessaires

* ``composer install`` pour la première utilisation
* ``composer update``

Si vous possédez déjà Yarn vous pouvez directement faire : ``yarn install`` pour installer les packages requis.

Sinon : ``npm install --global yarn yarn install``

Pour actualiser les scripts et feuilles de styles modifiées depuis /assets :
``yarn encore dev``

⚠️ N'oubliez pas de créeer un .env.local pour lancer le projet

## Mise en place de la base de données 

Tout d'abord, le projet fonctionne avec une base de données en SQLite. Pour cela vérifier que la ligne correspondante est bien décommentée/présente dans le ``env`` : 
``DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"``

Ensuite, il faut lancer la commande suivante : ``php bin/console doctrine:database:create`` 
Pour mettre en place/actualiser le schéma de la base de donnée : ``php bin/console d:s:u --force``

Afin de charger les fixtures sans souci et de mettre à jour la base de données correctement : ``php bin/console fixtures:update``
