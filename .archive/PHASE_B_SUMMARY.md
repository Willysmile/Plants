# Phase B: Import/Restauration - Documentation Complète

## Vue d'ensemble

Phase B ajoute une infrastructure d'import/restauration complète permettant aux administrateurs de restaurer des données à partir de sauvegardes créées avec Phase A.

**Status:** ✅ COMPLÈTEMENT IMPLÉMENTÉE

---

## Architecture

### Components Implémentés

#### 1. **ImportService** (`app/Services/ImportService.php`)
Service core gérant tous les aspects de l'import.

**Caractéristiques principales:**
- ✅ Validation complète du backup avant import
- ✅ 3 modes d'import: FRESH, MERGE, REPLACE
- ✅ Dry-run mode pour aperçu sans modification
- ✅ Gestion des transactions (rollback automatique en cas d'erreur)
- ✅ Remapping des IDs (photos, plantes, catégories, tags)
- ✅ Support des photos avec UUID-based naming
- ✅ Gestion des dépendances (évite les FK constraint errors)
- ✅ Validation structurelle du backup JSON

**Modes d'import:**

| Mode | Comportement | Cas d'usage |
|------|-------------|-----------|
| **FRESH** | Supprime tout + importe backup | Réinitialisation complète, migration |
| **MERGE** | Garde existant + ajoute/met à jour backup | Restauration sélective, défaut sûr |
| **REPLACE** | Remplace par référence (par `reference` du plant) | Mise à jour de données existantes |

#### 2. **PlantsImport Command** (`app/Console/Commands/PlantsImport.php`)
Commande Artisan CLI pour imports programmatiques.

**Usage:**
```bash
# Preview sans modification
php artisan plants:import /path/to/backup.zip --dry-run

# MERGE mode (défaut, sûr)
php artisan plants:import /path/to/backup.zip

# FRESH mode (réinitialisation)
php artisan plants:import /path/to/backup.zip --mode=FRESH

# REPLACE mode
php artisan plants:import /path/to/backup.zip --mode=REPLACE
```

**Output:**
- Validation du backup
- Tableau des comptes (plantes, photos, catégories, tags, historiques)
- Liste des avertissements
- Liste des erreurs éventuelles

#### 3. **BackupController - Endpoints d'import**
Nouvelles méthodes HTTP pour l'import via API.

**Endpoints:**
```
POST   /settings/backups/preview      Preview de l'import (dry-run)
POST   /settings/backups/import       Effectuer l'import réel
GET    /settings/backups/info         Infos sur un backup
```

#### 4. **Vue Settings - Section Import**
Interface utilisateur pour l'import.

**Fonctionnalités:**
- ✅ Sélection du backup à restaurer
- ✅ Choix du mode d'import (radio buttons)
- ✅ Aperçu avant import (dry-run)
- ✅ Confirmation requise avant import réel
- ✅ Affichage des statistiques
- ✅ Avertissements mode-spécifiques
- ✅ Messages de succès/erreur

---

## Process d'Import Détaillé

### 1. Extraction et Validation

```php
// Ouvre le ZIP
// Extrait backup.json
// Valide version (plants-backup-v1)
// Valide structure (présence plantes, catégories, etc.)
// Valide références (FK integrity)
// Extrait photos du dossier photos/
```

**Validations effectuées:**
- ✅ Version du backup
- ✅ Présence de backup.json
- ✅ Structure JSON valide
- ✅ Intégrité des références (plantes → catégories)
- ✅ Noms de plantes non vides
- ✅ Références circulaires

### 2. Mode FRESH
1. **Supprime toutes les données existantes:**
   - `Photo::truncate()`
   - `PlantHistory::truncate()`
   - `Plant::truncate()`
   - `Tag::truncate()`
   - `Category::truncate()`

2. **Importe nouvelles données:**
   - Crée catégories
   - Crée tags
   - Crée plantes
   - Attache tags aux plantes
   - Importe photos
   - Importe historiques

### 3. Mode MERGE
1. **Synchronise par clé unique:**
   - Catégories: Matched par `name`
   - Tags: Matched par `name`
   - Plantes: Matched par `reference` (ou créées si nouvelles)

2. **Comportement:**
   - Crée si n'existe pas
   - Met à jour les champs si existe
   - Ne supprime jamais

### 4. Mode REPLACE
1. **Remplace par référence:**
   - Cherche plante par `reference` existante
   - Met à jour tous les champs
   - Crée si `reference` ne correspond pas

### 5. Remapping des IDs

```php
// Mappings maintenus pendant l'import:
$categoryMapping = [old_id => new_id, ...]
$tagMapping = [old_id => new_id, ...]
$plantMapping = [old_id => new_id, ...]
$photoMapping = [old_id => new_id, ...]

// Utilisés pour mettre à jour les clés étrangères:
$plant->category_id = $categoryMapping[$oldCategoryId]
$plant->tags()->sync(array_map(fn($id) => $tagMapping[$id], $oldTagIds))
```

### 6. Gestion des Photos

```php
// Pour chaque photo dans le backup:
1. Cherche le fichier dans photos/
2. Génère UUID unique: $newFilename = Str::uuid() . ext
3. Stocke: Storage::disk('public')->put('plants/'.$newFilename, $content)
4. Crée enregistrement Photo avec nouveau filename
5. Mappe old_id → new_id pour les historiques
```

### 7. Transactions

```php
// Tout l'import est dans une transaction:
DB::transaction(function () {
    // Toutes les opérations
    // Si erreur: rollback automatique
    // Si succès: commit automatique
});
```

---

## Utilisation

### Via Interface Web

1. **Accéder à Settings → Sauvegardes & Exports**
2. **Section "Importer des données":**
   - Sélectionner un backup dans la liste
   - Choisir le mode (MERGE recommandé)
   - Cliquer "Aperçu avant import"
3. **Réviser l'aperçu:**
   - Vérifier les comptes (plantes, photos, etc.)
   - Lire les avertissements
   - Cliquer "Confirmer l'import"
4. **Confirmation de sécurité:**
   - Confirm dialog demande confirmation finale
   - Import effectué après confirmation
   - Page rechargée après succès

### Via Artisan CLI

```bash
# Dry-run pour vérifier
php artisan plants:import storage/app/backups/export_2025-10-21_19-26-42_abc.zip --dry-run

# MERGE mode (défaut)
php artisan plants:import storage/app/backups/export_2025-10-21_19-26-42_abc.zip

# FRESH mode
php artisan plants:import storage/app/backups/export_2025-10-21_19-26-42_abc.zip --mode=FRESH

# REPLACE mode
php artisan plants:import storage/app/backups/export_2025-10-21_19-26-42_abc.zip --mode=REPLACE
```

### Via API HTTP

```bash
# Preview
curl -X POST http://localhost:8000/settings/backups/preview \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $(csrf-token)" \
  -d '{
    "backup": "export_2025-10-21_19-26-42_abc.zip",
    "mode": "MERGE"
  }'

# Import réel
curl -X POST http://localhost:8000/settings/backups/import \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $(csrf-token)" \
  -d '{
    "backup": "export_2025-10-21_19-26-42_abc.zip",
    "mode": "MERGE",
    "confirmed": true
  }'
```

---

## Réponses API

### Preview Response (Dry-run)
```json
{
  "success": true,
  "result": {
    "status": "dry-run-completed",
    "mode": "MERGE",
    "dry_run": true,
    "counts": {
      "plants_imported": 30,
      "photos_imported": 45,
      "categories_synced": 8,
      "tags_synced": 12,
      "histories_imported": 120
    },
    "warnings": [
      "MERGE mode: Existing plants with same reference will be updated"
    ],
    "errors": [],
    "validation": {
      "version": "plants-backup-v1",
      "has_plants": true,
      "plants_count": 30,
      "has_categories": true,
      "has_tags": true,
      "has_histories": true,
      "errors": []
    }
  }
}
```

### Import Response (Réel)
```json
{
  "success": true,
  "message": "Import completed successfully",
  "result": {
    "status": "completed",
    "mode": "MERGE",
    "dry_run": false,
    "counts": {
      "plants_imported": 30,
      "photos_imported": 45,
      "categories_synced": 8,
      "tags_synced": 12,
      "histories_imported": 120
    },
    "warnings": [],
    "errors": []
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Import failed",
  "errors": [
    "Table 'backups' doesn't exist in archive",
    "Invalid JSON in backup.json"
  ]
}
```

---

## Gestion d'Erreurs

### Validations

| Erreur | Cause | Solution |
|--------|-------|----------|
| Fichier non trouvé | Path incorrect | Vérifier le chemin |
| ZIP invalide | Fichier corrompu | Télécharger un nouveau backup |
| backup.json manquant | Archive incomplete | Regénérer le backup |
| JSON invalide | Données corrompues | Regénérer le backup |
| Version non supportée | Backup trop ancien | Exporter depuis version actuelle |
| Référence FK invalide | Catégorie/Tag n'existe pas | Correction automatique avec warning |

### Erreurs d'Exécution

Toutes les opérations sont **transactionnelles**:
- Si erreur pendant import → **rollback automatique**
- Données existantes **non modifiées** en cas d'erreur
- État cohérent garanti

---

## Structure du Backup JSON

```json
{
  "version": "plants-backup-v1",
  "exported_at": "2025-10-21T19:26:42Z",
  "exported_by": "admin@example.com",
  "categories": [
    {
      "id": 1,
      "name": "Tropical",
      "description": "Plantes tropicales",
      "created_at": "2025-10-07T21:51:49Z",
      "updated_at": "2025-10-07T21:51:49Z"
    }
  ],
  "tags": [
    {
      "id": 1,
      "name": "Indoor",
      "color": "blue",
      "created_at": "2025-10-08T20:01:56Z",
      "updated_at": "2025-10-08T20:01:56Z"
    }
  ],
  "plants": [
    {
      "id": 1,
      "reference": "PLANT-001",
      "name": "Monstera Deliciosa",
      "category_id": 1,
      "description": "...",
      "created_at": "2025-10-07T21:52:00Z",
      "updated_at": "2025-10-07T21:52:00Z",
      "tags": [1, 2]
    }
  ],
  "plants_photos": [
    {
      "id": 1,
      "plant_id": 1,
      "filename": "monstera-01.jpg",
      "description": "Feuille détail",
      "created_at": "2025-10-08T20:15:30Z"
    }
  ],
  "plant_histories": [
    {
      "id": 1,
      "plant_id": 1,
      "type": "watering",
      "description": "Arrosé",
      "photo_id": 1,
      "created_at": "2025-10-21T19:00:00Z"
    }
  ]
}
```

---

## Avantages de cette Implémentation

✅ **Sécurité:**
- Transactions ACID
- Validations strictes
- Confirmation requise
- Dry-run preview

✅ **Flexibilité:**
- 3 modes adaptés à différents cas
- CLI + API + Web UI
- Remapping automatique des IDs

✅ **Fiabilité:**
- Gestion complète des erreurs
- Rollback sur erreur
- Validation structurelle
- Warnings informatifs

✅ **Usabilité:**
- Interface intuitive
- Aperçu avant import
- Messages clairs
- Statut en temps réel

---

## Prochaines Étapes: Phase C (Reset avec Recovery)

Phase C ajoutera:
- Commande `plants:reset` avec soft-delete
- Fenêtre de recovery 30 jours
- Audit logging
- Endpoint reset HTTP
- UI confirmation avec timer

---

**Documentation créée:** 21 octobre 2025
**Version:** 1.0
**Statut:** ✅ Complétée et prête pour Phase C
