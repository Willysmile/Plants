# 📚 Documentation - Plant Manager v1.13

## 🎯 Vue d'ensemble

**Plant Manager** est une application Laravel pour gérer une collection de plantes avec historique de maintenance, galerie photos, et système de référencement automatique.

**Version actuelle:** v1.13
**Date:** 22 octobre 2025

---

## 📋 Table des matières

1. [Installation & Setup](#installation--setup)
2. [Architecture](#architecture)
3. [Fonctionnalités](#fonctionnalités)
4. [Session en cours - Session v1.13](#session-en-cours---session-v113)
5. [Bugs corrigés dans cette session](#bugs-corrigés-dans-cette-session)
6. [Commandes utiles](#commandes-utiles)

---

## 🚀 Installation & Setup

### Prérequis
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js (pour Tailwind CSS)

### Installation

```bash
# Cloner le projet
git clone <repo-url>
cd plant_manager

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed

# Build assets
npm run build

# Serveur dev
php artisan serve
```

---

## 🏗️ Architecture

### Stack technologique
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **Database:** MySQL avec SoftDeletes
- **Images:** Intervention\Image (WebP)
- **Storage:** Local filesystem avec symlink

### Structure des répertoires

```
plant_manager/
├── app/
│   ├── Models/
│   │   ├── Plant.php
│   │   ├── Photo.php
│   │   ├── Tag.php
│   │   └── PlantHistory.php
│   ├── Services/
│   │   ├── ImageService.php
│   │   └── PhotoService.php
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   └── Console/
│       └── Commands/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── plants/
│   │   ├── components/
│   │   └── partials/
│   └── css/
├── public/
│   ├── storage/
│   ├── css/
│   └── js/
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/
```

### Base de données

**Tables principales:**
- `plants` - Plantes avec référence auto-générée
- `photos` - Photos avec format WebP
- `tags` - Étiquettes pour catégorisation
- `plant_histories` - Journal de maintenance
- `watering_histories` - Historique d'arrosage
- `fertilizing_histories` - Historique de fertilisation
- `repotting_histories` - Historique de rempotage

---

## ✨ Fonctionnalités

### Gestion des plantes
- ✅ Création/édition/suppression (soft-delete)
- ✅ Auto-génération de références (ex: `ORCHI-001`)
- ✅ Champs complets (famille, espèce, cultivar, etc.)
- ✅ Tags pour catégorisation
- ✅ Notes et infos diverses

### Galerie photos
- ✅ Photo principale + galerie
- ✅ Conversion automatique JPG → WebP (~55% économie)
- ✅ Système de swap (changer la photo principale)
- ✅ Galerie lightbox personnalisée
- ✅ Gestion des descriptions

### Historique de maintenance
- ✅ Arrosage
- ✅ Fertilisation
- ✅ Rempotage
- ✅ Infos diverses (notes libres)

### Interface
- ✅ Liste paginée des plantes
- ✅ Modal rapide pour consultation
- ✅ Formulaires complets
- ✅ Recherche et filtrage
- ✅ Design responsive (Tailwind)

---

## 🎯 Session en cours - Session v1.13

### Objectif
Corriger bugs critiques liés aux images, galerie et références.

### Commits de cette session

#### 1. **Affichage des images** (Début de session)
- ❌ Problème: Images n'apparaissaient pas sur `/plants`
- ✅ Causes multiples: Conversion WebP, main_photo null, migration
- ✅ Solutions:
  - Fixé `ImageService::convertToWebp()` pour sauver au bon endroit
  - Converti 132 JPG → WebP
  - Migré 40 plantes (assigné `main_photo`)

#### 2. **Swap de galerie** (Milieu de session)
- ❌ Problème: Après swap, l'ancienne photo principale disparaissait
- ✅ Solution: Créé `updateGalleryThumbnails()` pour remplacer le DOM

#### 3. **Lightbox dans modal** 
- ❌ Problème: Photo principale non-cliquable dans modal
- ✅ Solution: Appelé `GalleryManager.init()` dans modal display

#### 4. **Auto-génération référence**
- ❌ Problème: Référence n'était pas générée à la création
- ✅ Solution: Ajouté event `creating` dans le modèle

#### 5. **Bouton Régénérer**
- ❌ Problème: Bouton "Régénérer" ne fonctionnait pas
- ✅ Solution: Passé le bouton en paramètre à la fonction

#### 6. **Doublons de référence**
- ❌ Problème: Erreur `SQLSTATE[23000]` pour références dupliquées
- ✅ Causes: Logique incrémentation + soft-deleted
- ✅ Solutions:
  - Chercher MAX au lieu du dernier
  - Inclure soft-deleted dans génération

#### 7. **Scripts composants**
- ❌ Problème: `ReferenceError: regenerateReference is not defined`
- ✅ Solution: Ajouter `@stack('scripts')` dans le layout

---

## 🐛 Bugs corrigés dans cette session

### BUGFIX #1: ImageService - Conversion WebP

| Aspect | Détail |
|--------|--------|
| **Fichier** | `app/Services/ImageService.php` |
| **Problème** | Sauvegardait les WebP au mauvais endroit |
| **Solution** | Dynamique path au lieu de fixe |
| **Impact** | Images maintenant accessibles |
| **Branche** | v1.13 |

### BUGFIX #2: Gallery Swap - Ancienne photo disparaît

| Aspect | Détail |
|--------|--------|
| **Fichier** | `public/js/gallery-manager.js` |
| **Problème** | Après swap, ancienne photo principale invisible |
| **Solution** | `updateGalleryThumbnails()` + remplacement DOM |
| **Impact** | Galerie complète visible après swap |
| **Ligne** | 205-250 |

### BUGFIX #3: Modal Lightbox - Photo non-cliquable

| Aspect | Détail |
|--------|--------|
| **Fichier** | `public/js/modal-manager.js` |
| **Problème** | GalleryManager pas initialisé dans modal |
| **Solution** | Appeler `init()` dans la fonction display |
| **Impact** | Lightbox fonctionne en modal |
| **Ligne** | 84-88 |

### BUGFIX #4: Auto-génération référence

| Aspect | Détail |
|--------|--------|
| **Fichier** | `app/Models/Plant.php` |
| **Problème** | Référence pas générée à la création |
| **Solution** | Event `creating` + `generateReference()` |
| **Impact** | Références auto-créées |
| **Condition** | Si family fournie + reference vide |

### BUGFIX #5: Bouton Régénérer

| Aspect | Détail |
|--------|--------|
| **Fichier** | `resources/views/components/plant-form.blade.php` |
| **Problème** | Fonction non définie (event.target) |
| **Solution** | Passer `this` en paramètre |
| **Impact** | Bouton fonctionne maintenant |
| **Ligne** | 108, 305-354 |

### BUGFIX #6: Doublons de référence

| Aspect | Détail |
|--------|--------|
| **Fichiers** | `app/Models/Plant.php`, `PlantController.php` |
| **Problème** | Erreur `Duplicate entry` pour références |
| **Causes** | Logique MAX + soft-deleted non inclus |
| **Solutions** | Chercher MAX + `withTrashed()` |
| **Impact** | Pas de collision même avec soft-deleted |

### BUGFIX #7: Scripts composants non chargés

| Aspect | Détail |
|--------|--------|
| **Fichier** | `resources/views/layouts/app.blade.php` |
| **Problème** | `ReferenceError: regenerateReference is not defined` |
| **Solution** | Ajouter `@stack('scripts')` |
| **Impact** | Tous les scripts des composants chargés |
| **Ligne** | Avant `</body>` |

---

## 📊 Statistiques session

| Métrique | Valeur |
|----------|--------|
| **Bugs corrigés** | 7 |
| **Fichiers modifiés** | 9 |
| **Commits** | 9 |
| **Commits détails** | Voir CHANGELOG_SESSION_V113.md |
| **Images converties** | 132 JPG → WebP |
| **Plantes migrées** | 40 (`main_photo` assigné) |
| **Taille réduite** | ~55% (4KB → 1.8KB) |

---

## 🛠️ Commandes utiles

### Development
```bash
# Serveur Laravel
php artisan serve --port 8000

# Artisan tinker (console PHP)
php artisan tinker

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh --seed
```

### Images
```bash
# Convertir les images JPG en WebP
php artisan images:convert-to-webp

# Assigner photo principale aux plantes
php artisan plants:assign-main-photos

# Mode dry-run (prévisualisation)
php artisan images:convert-to-webp --dry-run
php artisan plants:assign-main-photos --dry-run
```

### Base de données
```bash
# Voir les plantes
php artisan tinker
> \App\Models\Plant::limit(5)->get()

# Chercher par référence
> \App\Models\Plant::where('reference', 'ORCHI-001')->first()

# Inclure soft-deleted
> \App\Models\Plant::withTrashed()->get()
```

### Assets
```bash
# Build CSS/JS
npm run build

# Watch mode
npm run dev
```

---

## 📖 Documentation des bugs

Chaque bug dispose d'une documentation détaillée:

- `docs/BUGFIX_IMAGE_DISPLAY.md` - Images et conversion WebP
- `docs/BUGFIX_GALLERY_SWAP.md` - Swap de galerie
- `docs/BUGFIX_MODAL_LIGHTBOX.md` - Lightbox dans modal
- `docs/BUGFIX_AUTO_REFERENCE.md` - Auto-génération références
- `docs/BUGFIX_BUTTON_REGENERATE.md` - Bouton régénérer
- `docs/BUGFIX_REFERENCE_DUPLICATE.md` - Doublons références
- `docs/BUGFIX_SCRIPTS_UNDEFINED.md` - Scripts non chargés

---

## 🚀 Prochaines étapes

### À faire
- [ ] Tester créations massives
- [ ] Vérifier performance galerie
- [ ] Tests d'intégration complets
- [ ] Documentation API

### Améliorations futures
- Édition batch
- Export données
- Statistiques avancées
- API REST complète

---

## 👤 Auteur & Historique

**Session:** Session v1.13
**Date:** 22 octobre 2025
**Branche:** v1.13

---

## 📞 Support

Pour les questions ou problèmes:
1. Vérifier `docs/` pour la documentation
2. Chercher dans `CHANGELOG_SESSION_V113.md`
3. Consulter les commits via Git

---

*Documentation générée le 22 octobre 2025*
