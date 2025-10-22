# Cahier des Charges Ultra Détaillé – Plant Manager v1.01

## Table des matières
1. [Présentation](#1-présentation)
2. [Objectifs et Périmètre](#2-objectifs-et-périmètre)
3. [Architecture Technique](#3-architecture-technique)
4. [Modèles de Données](#4-modèles-de-données)
5. [Fonctionnalités](#5-fonctionnalités)
6. [Interface Utilisateur](#6-interface-utilisateur)
7. [Accessibilité](#7-accessibilité)
8. [Internationalisation](#8-internationalisation)
9. [Sécurité](#9-sécurité)
10. [Performance](#10-performance)
11. [Sauvegarde et Restauration](#11-sauvegarde-et-restauration)
12. [Déploiement](#12-déploiement)
13. [Tests et Qualité](#13-tests-et-qualité)
14. [Maintenance et Support](#14-maintenance-et-support)
15. [Planning](#15-planning)
16. [Documentation](#16-documentation)

---

## 1. Présentation

Plant Manager est une application web destinée à la gestion de plantes d’intérieur. Elle permet à un utilisateur unique de suivre l’entretien, la croissance, l’historique et les photos de ses plantes, avec une interface moderne, accessible et multilingue.

---

## 2. Objectifs et Périmètre

- **Suivi complet** de chaque plante (entretien, historique, photos, notes).
- **Interface responsive** et accessible.
- **Aucune limite** sur le nombre de plantes ou de photos (hors contrainte disque).
- **Taille maximale d’une photo** : 1024 Ko.
- **Sauvegarde et restauration** faciles des données et photos.
- **Internationalisation** via fichiers externes.
- **Pas d’API** ni de gestion multi-utilisateurs pour la v1.01.

---

## 3. Architecture Technique

- **Backend** : Laravel 12.x LTS (PHP 8.2+)
- **Frontend** : Blade, Alpine.js 3.x, Tailwind CSS 3.x (CDN)
- **Base de données** : MySQL 8.0+
- **ORM** : Eloquent
- **Stockage photos** : système de fichiers local (`storage/app/public/plants`)
- **Authentification** : Laravel Breeze (compte unique)
- **Tests** : Pest
- **Déploiement** : Docker (Sail) ou installation classique

---

## 4. Modèles de Données

### 4.1 Plante (`plants`)
- id (int, PK)
- name (string, obligatoire)
- scientific_name (string, optionnel)
- type (string, obligatoire)
- purchase_date (datetime, optionnel)
- watering_frequency (string, obligatoire)
- light_requirement (string, obligatoire)
- temperature_min (float, optionnel)
- temperature_max (float, optionnel)
- humidity_level (int, optionnel)
- main_photo (string, optionnel)
- notes (text, optionnel)
- created_at, updated_at

### 4.2 Photo (`photos`)
- id (int, PK)
- plant_id (FK, obligatoire)
- filename (string, obligatoire)
- uploaded_at (datetime, obligatoire)
- description (string, optionnel)

### 4.3 Historique (`histories`)
- id (int, PK)
- plant_id (FK, obligatoire)
- action_type (string, obligatoire)  
  (arrosage, rempotage, fertilisation, taille, etc.)
- action_date (datetime, obligatoire)
- notes (text, optionnel)

---

## 5. Fonctionnalités

### 5.1 Gestion des Plantes
- Création, modification, suppression, consultation
- Recherche et filtres (nom, type, fréquence, etc.)
- Ajout de notes personnalisées
- Affichage des informations détaillées

### 5.2 Gestion des Photos
- Upload (max 1024 Ko, JPG/PNG)
- Redimensionnement automatique
- Galerie par plante
- Suppression et édition de description
- Téléchargement groupé (zip)

### 5.3 Historique et Suivi
- Ajout d’actions (arrosage, rempotage, etc.)
- Timeline visuelle par plante
- Notes sur chaque action
- Export de l’historique (CSV/JSON)

### 5.4 Notifications et Rappels
- Rappels d’arrosage/fertilisation (affichage dans l’interface)
- Système de notifications internes (pas d’email pour v1.01)

### 5.5 Sauvegarde et Restauration
- Export complet de la base (SQL)
- Export du dossier photos (zip)
- Import de sauvegarde (base + photos)
- Interface dédiée avec vérification et messages d’erreur clairs

---

## 6. Interface Utilisateur

- **Responsive** (mobile, tablette, desktop)
- **Navigation claire** (menu, fil d’Ariane)
- **Tableaux, cartes, galeries**
- **Formulaires accessibles** (labels, erreurs, aides)
- **Thème clair/sombre (optionnel)**

---

## 7. Accessibilité

- Contrastes respectés (WCAG AA)
- Navigation clavier complète (tabindex, focus visible)
- Attributs ARIA sur les composants interactifs
- Tests d’accessibilité (Lighthouse, axe)
- Textes alternatifs sur toutes les images

---

## 8. Internationalisation

- Textes de l’interface dans des fichiers externes (`resources/lang/fr.json`, `en.json`, etc.)
- Sélecteur de langue dans l’interface
- Ajout facile de nouvelles langues

---

## 9. Sécurité

- Authentification unique (Breeze)
- CSRF, XSS, SQL Injection protégés par Laravel
- Validation stricte des formulaires et uploads
- Limitation de la taille des fichiers uploadés
- Permissions sur les fichiers/dossiers sensibles

---

## 10. Performance

- Cache des vues et des requêtes
- Lazy loading des images
- Compression/minification des assets
- Indexation des tables SQL
- Monitoring via Laravel Telescope

---

## 11. Sauvegarde et Restauration

- Boutons d’export/import dans l’interface
- Export SQL de la base de données
- Export ZIP du dossier photos
- Procédure d’import guidée (vérification de la structure, messages d’erreur)
- Documentation utilisateur détaillée

---

## 12. Déploiement

- **Classique** : PHP/MySQL/Composer sur serveur Linux
- **Docker/Sail** : pour portabilité maximale
- **Procédure de migration** : commandes artisan, gestion des clés, permissions
- **Sauvegarde automatique** (cron ou tâche planifiée, optionnel)

---

## 13. Tests et Qualité

- Tests unitaires (Pest)
- Tests fonctionnels (CRUD, uploads, historique)
- Tests d’accessibilité
- Couverture de code > 70%
- CI/CD (GitHub Actions, optionnel)

---

## 14. Maintenance et Support

- Mises à jour régulières (Laravel, dépendances)
- Documentation technique et utilisateur
- Système de logs et alertes
- Procédure de restauration rapide

---

## 15. Planning

- **Semaine 1-2** : Setup, modèles, migrations, CRUD plantes
- **Semaine 3-4** : Photos, historique, interface responsive
- **Semaine 5** : Sauvegarde/restauration, accessibilité, internationalisation
- **Semaine 6** : Tests, documentation, déploiement

---

## 16. Documentation

- Guide d’installation (classique et Docker)
- Guide utilisateur (ajout plante, photo, historique…)
- Procédure de sauvegarde/restauration
- FAQ et support

---

**Version** : 1.01  
**Date** : 05/10/2025  
**Auteur** : F.Rabillard & IA