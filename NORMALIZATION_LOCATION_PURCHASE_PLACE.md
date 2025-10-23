# 🗂️ Normalisation: Emplacement et Lieu d'Achat

## 📋 Récapitulatif

Le système a été amélioré avec des tables normalisées pour **Emplacement** et **Lieu d'Achat**, permettant une meilleure organisation et réutilisabilité des données.

## 📊 Structure Base de Données

### Table `locations`

```sql
CREATE TABLE locations (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    room VARCHAR(255),
    light_level VARCHAR(255),
    humidity_level INT,
    temperature DECIMAL(5,2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Exemples:**
- Salon, Cuisine, Chambre
- Salle de bain, Terrasse, Vérandah
- Bureau, Sous-sol

### Table `purchase_places`

```sql
CREATE TABLE purchase_places (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    address VARCHAR(255),
    phone VARCHAR(20),
    website VARCHAR(255),
    type VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Exemples:**
- Jardinerie locale
- Pépinière XYZ
- Marché fermier
- Site en ligne
- Ami/Famille

### Table `plants` (modifications)

```sql
ALTER TABLE plants 
ADD COLUMN location_id BIGINT NULLABLE,
ADD COLUMN purchase_place_id BIGINT NULLABLE,
ADD FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE SET NULL,
ADD FOREIGN KEY (purchase_place_id) REFERENCES purchase_places(id) ON DELETE SET NULL;
```

Les colonnes `location` et `purchase_place` (string) sont conservées pour compatibilité mais devraient être remplacées progressivement par `location_id` et `purchase_place_id`.

## 🔗 Relations Eloquent

### Plant Model

```php
// Relation: Une plante a un emplacement
$plant->location()  // Returns Location model

// Relation: Une plante a un lieu d'achat
$plant->purchasePlace()  // Returns PurchasePlace model
```

### Location Model

```php
// Relation: Un emplacement peut avoir plusieurs plantes
$location->plants()  // Returns Collection of Plants
```

### PurchasePlace Model

```php
// Relation: Un lieu d'achat peut avoir plusieurs plantes
$purchasePlace->plants()  // Returns Collection of Plants
```

## 🚀 Usage dans les Vues

### Affichage Simplifié

```blade
<!-- Avant (string) -->
{{ $plant->location }}

<!-- Après (relationship) -->
{{ $plant->location->name ?? 'N/A' }}
{{ $plant->location->room ?? '' }}
{{ $plant->location->light_level ?? '' }}

<!-- Lieu d'achat -->
{{ $plant->purchasePlace->name ?? 'N/A' }}
{{ $plant->purchasePlace->type ?? '' }}
{{ $plant->purchasePlace->address ?? '' }}
```

## 📝 Fichiers Modifiés

### Modèles
- `app/Models/Plant.php` - Ajout des relations location() et purchasePlace()
- `app/Models/Location.php` - Ajout des relations et des fillable fields
- `app/Models/PurchasePlace.php` - Ajout des relations et des fillable fields

### Migrations
- `2025_10_23_add_location_and_purchase_place_fk_to_plants.php` - Ajout FK
- `2025_10_23_enhance_locations_table.php` - Colonnes supplémentaires
- `2025_10_23_enhance_purchase_places_table.php` - Colonnes supplémentaires
- `2025_10_23_migrate_location_and_purchase_place_data.php` - Migration des données

### Seeders
- `database/seeders/LocationSeeder.php` - Données de base
- `database/seeders/PurchasePlaceSeeder.php` - Données de base

## ✅ Bénéfices

1. **Réutilisabilité** - Chaque location/lieu d'achat peut être utilisé par plusieurs plantes
2. **Maintenance** - Modification d'une location met à jour toutes les plantes concernées
3. **Statistiques** - Compter les plantes par location ou lieu d'achat
4. **Détails enrichis** - Chaque location/lieu a des métadonnées (lumière, humidité, etc.)
5. **Intégrité des données** - Évite les doublons et les incohérences

## 🔄 Migration des Données

La migration `2025_10_23_migrate_location_and_purchase_place_data.php` :
- Crée automatiquement des enregistrements Location/PurchasePlace pour chaque valeur string unique
- Remplit les colonnes `location_id` et `purchase_place_id` avec les IDs appropriés
- Conserve les colonnes string pour compatibilité

## 📋 À Faire

- [ ] Mettre à jour les formulaires Create/Edit pour utiliser les selects FK
- [ ] Créer des pages de gestion pour Locations et PurchasePlaces
- [ ] Ajouter des validations personnalisées
- [ ] Créer des rapports/statistiques par location
- [ ] Supprimer progressivement les colonnes string une fois la migration complète
