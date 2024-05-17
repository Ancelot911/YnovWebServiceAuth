# API d'Authentification

Ce projet est une application développée avec CodeIgniter4, intégrant un webservice d'authentification utilisant des JWT pour sécuriser les communications entre les clients et le serveur.

## Fonctionnalités

- **Création d'un compte utilisateur**
- **Récupération d'un compte utilisateur**
- **Modification d'un compte utilisateur**
- **Création d'un access token à partir d'un refresh token**
- **Création d'un token de connection**
- **Confirme la validité d'un access token**

## Prérequis

Assurez-vous d'avoir installé PHP 7.3 ou supérieur et Composer sur votre machine. CodeIgniter4 nécessite également une base de données pour stocker les informations d'utilisateur.

## Installation
1. Installez les dépendances PHP via Composer :
    ```bash
   composer install
   ```

## Configuration

Avant de démarrer l'application, vous devez configurer l'environnement en suivant ces étapes :

1. **Configurer le fichier `.env`** :
   - Copiez le fichier `.env.example` fourni dans le répertoire racine :
     ```bash
     cp .env.example .env
     ```
   - Ouvrez le fichier `.env` copié et remplissez les informations de configuration spécifiques à votre environnement. Assurez-vous de remplacer les valeurs par défaut, y compris `votre_clé_secrète_ici` pour la clé JWT, par des valeurs appropriées pour votre configuration.


- Utiliser le conteneur Docker :
   ```bash
    docker-compose up -d
     ```
  
2. **Configurez votre base de données** :

   ```bash
   php spark migrate
   ```
3. **Lancer le serveur** :
   ```bash
   php spark serve 
   ```