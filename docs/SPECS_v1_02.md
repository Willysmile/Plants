# Cahier des Charges Ultra Détaillé – Plant Manager v1.02

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

Plant Manager est une application web destinée à la gestion de plantes d'intérieur. Elle permet à un utilisateur unique de suivre l'entretien, la croissance, l'historique et les photos de ses plantes, avec une interface moderne, accessible et responsive. La v1.02 améliore significativement l'interface utilisateur et la gestion des médias par rapport à la v1.01.

---

## 2. Objectifs et Périmètre

- **Suivi complet** de chaque plante (entretien, historique, photos, notes).
- **Interface responsive et professionnelle** utilisant exclusivement Tailwind CSS (classes standard).
- **Aucune limite** sur le nombre de plantes ou de photos (hors contrainte disque).
- **Taille maximale d'une photo** : 1024 Ko.
- **Galerie optimisée** : miniatures 200x200px, images principales adaptatives.
- **Sauvegarde et restauration** faciles des données et photos.
- **Internationalisation** via fichiers externes.
- **Pas d'API** ni de gestion multi-utilisateurs pour la v1.02.

---

## 3. Architecture Technique

- **Backend** : Laravel 12.x LTS (PHP 8.2+)
- **Frontend** : Blade, Tailwind CSS 3.x (CDN)
- **Base de données** : MySQL 8.0+
- **ORM** : Eloquent
- **Stockage photos** : système de fichiers local (`storage/app/public/plants`)
- **Authentification** : Laravel Breeze (compte unique)
- **Tests** : Pest
- **Déploiement** : Docker (Sail) ou installation classique
- **Styling** : Tailwind CSS uniquement (pas de CSS personnalisé en v1.02)

---

## 4. Modèles de Données

### 4.1 Plante (`plants`)
- id (int, PK)
- name (string, obligatoire)
- scientific_name (string, optionnel)
- category_id (FK, optionnel) - Relation avec la table categories
- purchase_date (datetime, optionnel)
- watering_frequency (enum, obligatoire)
  - rare, normal, frequent
- light_requirement (enum, obligatoire)
  - low, medium, bright
- temperature_min (float, optionnel)
- temperature_max (float, optionnel)
- humidity_level (int, optionnel, 0-100%)
- main_photo (string, optionnel) - Chemin vers l'image principale
- description (text, optionnel)
- notes (text, optionnel)
- created_at, updated_at

### 4.2 Catégorie (`categories`)
- id (int, PK)
- name (string, obligatoire, unique)
- description (text, optionnel)
- created_at, updated_at

### 4.3 Photo (`photos`)
- id (int, PK)
- plant_id (FK, obligatoire)
- filename (string, obligatoire) - Chemin du fichier
- uploaded_at (datetime, obligatoire)
- description (string, optionnel)
- created_at, updated_at

### 4.4 Historique (`histories`)
- id (int, PK)
- plant_id (FK, obligatoire)
- action_type (enum, obligatoire)
  - watering, repotting, fertilizing, pruning, treatment, observation
- action_date (datetime, obligatoire)
- notes (text, optionnel)
- created_at, updated_at

---

## 5. Fonctionnalités

### 5.1 Gestion des Plantes
- **CRUD complet** : création, modification, suppression, consultation
- **Recherche** par nom et nom scientifique
- **Filtres** par catégorie, fréquence d'arrosage, besoin en lumière
- **Affichage en grille** responsive (1 col mobile, 2 sm, 3 md, 4 lg)
- **Fiche détaillée** occupant ~95% de la hauteur de l'écran
- **Gestion des catégories** (CRUD simple)

### 5.2 Gestion des Photos
- **Upload** : max 1024 Ko, formats JPG/PNG
- **Redimensionnement automatique** lors de l'upload
- **Galerie par plante** avec :
  - Miniatures : 200x200px (hauteur comme référence)
  - Image principale : adaptative, max 60vh
  - Lightbox pour visualisation agrandie
- **Description modifiable** pour chaque photo
- **Suppression** avec confirmation
- **Organisation** : image principale + galerie secondaire

### 5.3 Historique et Suivi
- **Ajout d'actions** : arrosage, rempotage, fertilisation, taille, traitement, observation
- **Timeline visuelle** par plante (optionnel pour v1.02)
- **Notes** sur chaque action
- **Export de l'historique** : CSV/JSON (optionnel pour v1.02)

### 5.4 Notifications et Rappels
- **Affichage des rappels** dans l'interface (pas d'email pour v1.02)
- **Indicateurs visuels** pour les plantes nécessitant de l'eau ou de la fertilisation

### 5.5 Sauvegarde et Restauration
- **Export complet** de la base de données (SQL)
- **Export du dossier photos** en ZIP
- **Import guidé** de sauvegarde (base + photos)
- **Vérification de l'intégrité** des imports
- **Messages d'erreur clairs** et récupération

---

## 6. Interface Utilisateur

### 6.1 Pages Principales

#### Page Index (Liste des plantes)
- **Grille responsive** de cartes
- **Chaque carte affiche** :
  - Image principale (h-48)
  - Nom et nom scientifique
  - Catégorie (badge)
  - Icônes d'arrosage et lumière
  - Actions rapides (édition, suppression)
- **Barre de recherche/filtres** en haut
- **Bouton "Ajouter une plante"** prominent

#### Page Show (Détail plante)
- **Hauteur** : 98vh (presque tout l'écran)
- **En-tête** : titre, boutons Modifier/Retour
- **Contenu principal** (~66% de la hauteur) :
  - Image principale (adaptative, max 60vh)
  - Colonne latérale avec description, catégorie, besoins (arrosage/lumière)
- **Galerie** (~34% de la hauteur, section inférieure) :
  - Grille auto-fill de miniatures 200x200px
  - Défilement indépendant
  - Lightbox au clic
- **Responsive** : une colonne sur mobile, deux sur desktop

#### Page Create/Edit
- **Formulaire structuré** avec sections
- **Validations** côté serveur et feedback utilisateur
- **Upload photo** avec preview
- **Champs optionnels** clairement marqués
- **Boutons** Enregistrer/Annuler

### 6.2 Composants Réutilisables
- **Cards** : ombres, arrondis, transitions
- **Formulaires** : labels, inputs, textareas, selects
- **Boutons** : couleurs cohérentes (vert pour actions, rouge pour suppression, jaune pour édition)
- **Modales** de confirmation (suppression, etc.)
- **Notifications** (flash messages)

### 6.3 Thème et Couleurs
- **Palette** :
  - Primaire : vert (green-600, green-700)
  - Accentuation : bleu, jaune, rouge
  - Neutres : gris (gray-50 à gray-900)
- **Fond** : gray-50
- **Texte** : gray-900 par défaut
- **Ombres** : shadow légères et modérées

---

## 7. Accessibilité

- **Contrastes** respectés (WCAG AA minimum)
- **Navigation clavier** complète (Tab, Enter, Escape)
- **Focus visible** sur tous les éléments interactifs
- **Attributs ARIA** sur les composants complexes (modales, menus)
- **Textes alternatifs** sur toutes les images
- **Labels associés** à tous les inputs
- **Gestion des erreurs** claires et explicites
- **Tests réguliers** avec Lighthouse et axe DevTools

---

## 8. Internationalisation

- **Support multilingue** : français (par défaut), anglais (optionnel en v1.02)
- **Fichiers de traduction** : `resources/lang/fr.json`, `en.json`
- **Sélecteur de langue** dans le menu utilisateur (optionnel)
- **Dates et formats** : localisés selon la langue
- **Procédure simple** pour ajouter une nouvelle langue

---

## 9. Sécurité

- **Authentification** : Laravel Breeze (session-based, compte unique)
- **Protection CSRF** sur tous les formulaires
- **Protection XSS** : échappement des données utilisateur
- **Protection SQL Injection** : utilisation d'Eloquent avec paramètres liés
- **Validation stricte** des formulaires côté serveur
- **Limitation des uploads** : taille max 1024 Ko, extensions whitelist (jpg, png)
- **Permissions fichiers** : 644 pour fichiers, 755 pour dossiers
- **Pas d'exposition** de répertoires sensibles (storage non accessible directement)
- **Sanitization** des noms de fichiers uploadés

---

## 10. Performance

- **Cache des vues** Blade compilées
- **Lazy loading** des images (optionnel, utiliser picture/srcset)
- **Compression** des images au redimensionnement
- **CDN Tailwind** : utilisation du CDN officiel (version 3.x)
- **Indexation** des tables SQL (plant_id sur photos/histories, category_id sur plants)
- **Pagination** de la liste des plantes (12-20 par page)
- **Monitoring** optionnel via Laravel Telescope
- **Assets** : minification automatique en production

---

## 11. Sauvegarde et Restauration

### 11.1 Export
- **Export Base** : SQL complet de la base de données
- **Export Photos** : ZIP du dossier `storage/app/public/plants`
- **Boutons** dans une section dédiée (Settings/Maintenance)
- **Confirmations** avant export
- **Historique d'exports** (optionnel)

### 11.2 Import
- **Procédure guidée** en plusieurs étapes
- **Vérification** de la structure des fichiers
- **Messages d'erreur** détaillés en cas de problème
- **Restauration transactionnelle** (rollback si erreur)
- **Notification** de succès/erreur à l'utilisateur

### 11.3 Automatisation (Optionnel)
- **Cron job** pour exports quotidiens/hebdomadaires
- **Stockage** dans un dossier `backups/` en dehors du web root

---

## 12. Déploiement

### 12.1 Installation Classique
1. Cloner le repo
2. `composer install`
3. Copier `.env.example` → `.env`
4. `php artisan key:generate`
5. Configurer la base de données
6. `php artisan migrate --seed` (optionnel)
7. `php artisan storage:link`
8. Configurer le serveur web (Apache/Nginx)
9. Permissions : `www-data:www-data` sur `storage/` et `bootstrap/cache/`

### 12.2 Déploiement Docker/Sail
1. `composer install`
2. `./vendor/bin/sail up -d`
3. `./vendor/bin/sail artisan migrate --seed`
4. Accès : `http://localhost`

### 12.3 Procédure de Migration
- Guide pas-à-pas pour passer de v1.01 à v1.02
- Scripts de migration des données existantes
- Gestion des changements de schéma

---

## 13. Tests et Qualité

- **Tests unitaires** (Pest) : modèles, services
- **Tests fonctionnels** :
  - CRUD plantes
  - Upload/suppression photos
  - Gestion historique
  - Sauvegarde/restauration
- **Tests d'accessibilité** : Lighthouse, axe DevTools
- **Couverture de code** : > 70%
- **CI/CD optionnel** : GitHub Actions

---

## 14. Maintenance et Support

- **Mises à jour régulières** (Laravel, Tailwind, dépendances)
- **Documentation** :
  - Guide d'installation (classique et Docker)
  - Guide utilisateur complet
  - FAQ et troubleshooting
  - Documentation technique (architecture, modèles)
- **Logs** : stockés dans `storage/logs/`
- **Procédure de restauration** rapide et documentée

---

## 15. Planning v1.02

### Phase 1 (Semaines 1-2)
- ✅ Setup initial
- ✅ CRUD plantes et catégories
- ✅ Formulaires avec Tailwind

### Phase 2 (Semaines 3-4)
- ✅ Système photos (upload, stockage, galerie)
- ✅ Page show responsive avec galerie intégrée
- ✅ Lightbox pour visualisation agrandie

### Phase 3 (Semaine 5)
- ⏳ Historique des actions
- ⏳ Sauvegarde/restauration
- ⏳ Accessibilité améliorée

### Phase 4 (Semaine 6)
- ⏳ Tests complets (Pest)
- ⏳ Documentation utilisateur
- ⏳ Déploiement et ajustements

---

## 16. Documentation

### 16.1 Installation
- Guide pour installation classique
- Guide pour Docker/Sail
- Configuration recommandée (MySQL, PHP, Nginx)

### 16.2 Utilisation
- Ajouter une plante (formulaire complet)
- Gérer les photos (upload, édition, suppression)
- Consulter l'historique
- Effectuer une sauvegarde/restauration

### 16.3 Technique
- Architecture et diagrammes
- Modèles de données et relations
- Structure des dossiers
- API des commandes artisan

### 16.4 FAQ
- Taille maximale des photos
- Formats acceptés
- Procédure de récupération en cas d'erreur

---

## Changements v1.01 → v1.02

### Améliorations UI/UX
- Interface entièrement redessinée avec Tailwind CSS (classes standard uniquement)
- Page show optimisée : hauteur 98vh, galerie en bas
- Grille responsive pour la liste des plantes
- Miniatures 200x200px, hauteur comme référence
- Lightbox pour visualisation agrandie

### Améliorations Données
- Ajout de la table `categories` et relation many-to-one
- Énums pour `watering_frequency`, `light_requirement`, `action_type`
- Champ `description` sur la table `plants`
- Séparation claire entre image principale et galerie

### Améliorations Techniques
- Suppression des CSS personnalisés au profit de Tailwind pur
- Structuration modulaire des vues Blade
- Meilleure gestion des médias (redimensionnement automatique)
- Performance optimisée (pagination, indexation)

---

**Version** : 1.02  
**Date** : 16/10/2025  
**Auteur** : F.Rabillard & IA  
**Statut** : En développement