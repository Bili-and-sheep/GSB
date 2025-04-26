# GSB - Frais

## Contexte du projet

Ce projet s'inscrit dans le cadre de l'épreuve E6 du BTS SIO (option SLAM). Il consiste en la conception et le développement d'une application web de gestion de frais professionnels pour le laboratoire pharmaceutique Galaxy Swiss Bourdin (GSB).

L'objectif est de **moderniser**, **standardiser** et **sécuriser** la gestion des frais des visiteurs médicaux.

- **Période de réalisation :** Septembre 2024 – Avril 2025
- **Lieu :** Osint Friendly
- **Modalité :** Projet individuel

## Compétences travaillées

- Concevoir et développer une solution applicative.
- Gérer des données.
- Assurer la maintenance corrective ou évolutive d'une solution applicative.

## Objectifs principaux

- **Standardiser** la gestion des frais.
- **Remplacer** les anciens systèmes papiers et logiciels obsolètes.
- **Garantir** un suivi fiable des remboursements.
- **Faciliter** la gestion des frais pour les visiteurs médicaux et le service comptable.

## Fonctionnalités principales

### Pour les visiteurs médicaux :
- Authentification sécurisée.
- Saisie et modification des frais (forfaitisés et hors forfait) chaque mois.
- Consultation de l'état de remboursement sur une période d'un an.

### Pour le service comptable :
- Validation mensuelle des frais soumis.
- Modification et suppression des frais non valides.
- Traitement des paiements et suivi des remboursements.

## Ressources et environnement

- **Framework :** Symfony (PHP)
- **Architecture :** MVC (Modèle-Vue-Contrôleur)
- **Base de données :** MySQL
- **Serveur de développement :** Symfony CLI
- **Version de PHP recommandée :** ≥ 8.1

## Installation du projet

1. **Cloner le dépôt :**
```bash
git clone https://github.com/Bili-and-sheep/GSB.git
cd GSB
```

2. **Configurer les variables d'environnement :**
```bash
nano .env.local
```

3. **Installer les dépendances :**
```bash
composer install
```

4. **Configurer la base de données :**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

5. **Démarrer le serveur :**
```bash
symfony serve -d
```

6. **Accéder à l'application :**
   [http://localhost:8000](http://localhost:8000)

## Mise en place initiale

1. **Importer les données :**
   - Rendez-vous sur la route `/import` ou via la section « BigBoy » dans le menu.
   - Cliquer sur les boutons dans l'ordre indiqué pour importer toutes les données.

2. **Modifier les utilisateurs par défaut :**
   - Accéder à `/user` pour modifier les rôles des utilisateurs.
   - Utilisateur de démo :
      - **Email :** `villechalane.louis@gsb.fr`
      - **Mot de passe :** `jux7g`

3. **Connexion à l'application :**
   - Se connecter à `/login` avec un compte ayant les rôles adaptés.

> **Attention :** sans les bons rôles, certaines parties de l'application ne seront pas accessibles.

## Structure du projet

```
GSB/
├── config/         # Configuration Symfony
├── src/            # Contrôleurs, entités, services
├── templates/      # Vues Twig
├── migrations/     # Migrations Doctrine
├── public/         # Ressources publiques
├── tests/          # Tests unitaires et fonctionnels
├── assets/         # Fichiers frontend
```

## Structure de la base de données

![Structure de la base de données GSB](public/GSB_DDC.png)

- Tables principales : `User`, `FraisForfait`, `FicheFrais`, `LigneFraisForfait`, `LigneFraisHorsForfait`

## Tests

Lancer les tests unitaires et fonctionnels :
```bash
php bin/console doctrine:schema:create --env=test
php bin/phpunit
```

## Maintenance prévue

- **Maintenance corrective :**
   - Correction des bugs signalés.

- **Maintenance adaptative :**
   - Intégration de nouvelles fonctionnalités sur retour utilisateur.
   - Mise à jour des dépendances Symfony et sécurité PHP.

## Sécurité et accès

- Authentification sécurisée via Symfony Security.
- Gestion des rôles utilisateurs (Visiteur, Comptable).
- Authentification à deux facteurs (2FA) pour les opérations sensibles.
- Validation côté serveur des données saisies.

## Modalités d'accès aux productions

- Application accessible via URL locale : [http://localhost:8000](http://localhost:8000)

---

**Auteur :** Bili-and-sheep  
**Session :** BTS SIO 2025  
**Option :** SLAM

Projet réalisé dans le cadre de l'épreuve E6.
