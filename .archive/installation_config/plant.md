# Création de la table `plants` dans Laravel – Guide détaillé

Ce guide explique, étape par étape, comment créer la table `plants` avec tous les champs nécessaires dans un projet Laravel, en utilisant les migrations.

---

## 1. Générer le modèle et la migration

```bash
php artisan make:model Plant -m
```
- **But** : Crée le modèle Eloquent `Plant` (`app/Models/Plant.php`) et la migration associée dans `database/migrations/`.
- **Option `-m`** : Génère automatiquement le fichier de migration.

---

## 2. Éditer la migration

- Ouvre le fichier créé dans `database/migrations/` (nommé `xxxx_xx_xx_create_plants_table.php`).
- Remplace le contenu de la méthode `up()` par la structure complète des champs souhaités (voir exemple plus bas).
- **Commente chaque champ** pour garder une documentation claire.

---

## 3. Exemple de migration commentée

```php
// filepath: database/migrations/xxxx_xx_xx_create_plants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('name'); // Nom commun (obligatoire)
            $table->string('scientific_name')->nullable(); // Nom scientifique (facultatif)
            $table->timestamp('created_at')->useCurrent(); // Date d’ajout (auto)
            $table->timestamp('updated_at')->useCurrent()->nullable(); // Date de modif (auto)

            $table->date('purchase_date')->nullable(); // Date d’achat
            $table->string('purchase_place')->nullable(); // Lieu d’achat
            $table->decimal('purchase_price', 8, 2)->nullable(); // Prix d’achat

            $table->unsignedBigInteger('category_id'); // Catégorie (clé étrangère)
            $table->text('description')->nullable(); // Notes libres

            $table->unsignedTinyInteger('watering_frequency'); // Fréquence d’arrosage (1 à 5)
            $table->date('last_watering_date')->nullable(); // Dernière date d’arrosage

            $table->unsignedTinyInteger('light_requirement'); // Besoin en lumière (1 à 5)
            $table->float('temperature_min')->nullable(); // Température min idéale
            $table->float('temperature_max')->nullable(); // Température max idéale
            $table->string('humidity_level')->nullable(); // Humidité de l’air
            $table->string('soil_humidity')->nullable(); // Hydrométrie idéale
            $table->decimal('soil_ideal_ph', 3, 1)->nullable(); // pH idéal du sol
            $table->string('soil_type')->nullable(); // Type de substrat

            $table->string('info_url')->nullable(); // URL d’information
            $table->string('main_photo')->nullable(); // Photo principale
            $table->string('location')->nullable(); // Emplacement actuel
            $table->string('pot_size')->nullable(); // Taille du pot

            $table->string('health_status')->nullable(); // État de santé
            $table->date('last_fertilizing_date')->nullable(); // Dernière fertilisation
            $table->unsignedTinyInteger('fertilizing_frequency')->nullable(); // Fréquence d’engrais (1 à 5)
            $table->date('last_repotting_date')->nullable(); // Dernier rempotage
            $table->date('next_repotting_date')->nullable(); // Prochain rempotage

            $table->string('growth_speed')->nullable(); // Vitesse de croissance
            $table->float('max_height')->nullable(); // Hauteur max en cm
            $table->boolean('is_toxic')->nullable(); // Toxique animaux/enfants
            $table->string('flowering_season')->nullable(); // Période de floraison
            $table->unsignedTinyInteger('difficulty_level')->nullable(); // Difficulté d’entretien (1 à 5)

            $table->boolean('is_indoor')->nullable(); // Plante d’intérieur
            $table->boolean('is_outdoor')->nullable(); // Plante d’extérieur
            $table->boolean('is_favorite')->nullable(); // Plante favorite
            $table->boolean('is_archived')->nullable(); // Plante archivée
            $table->date('archived_date')->nullable(); // Date d’archivage
            $table->text('archived_reason')->nullable(); // Raison de l’archivage

            // Clé étrangère vers categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plants');
    }
};
```

---

## 4. Créer la table `categories` (si pas déjà fait)

Avant de migrer, crée la table `categories` pour éviter une erreur de clé étrangère.

```bash
php artisan make:model Category -m
```
- Ajoute au moins un champ `name` dans la migration de `categories`.

---

## 5. Lancer la migration

```bash
php artisan migrate
```
- **But** : Crée physiquement la table `plants` (et `categories` si migration présente) dans la base de données.

---

## 6. Vérifier la création

- Connecte-toi à ta base MySQL/MariaDB et vérifie la présence de la table :
```sql
SHOW TABLES;
DESCRIBE plants;
```

---

## 7. (Optionnel) Ajouter les champs dans `$fillable` du modèle Plant

Dans `app/Models/Plant.php` :
```php
protected $fillable = [
    'name', 'scientific_name', 'purchase_date', 'purchase_place', 'purchase_price',
    'category_id', 'description', 'watering_frequency', 'last_watering_date',
    'light_requirement', 'temperature_min', 'temperature_max', 'humidity_level',
    'soil_humidity', 'soil_ideal_ph', 'soil_type', 'info_url', 'main_photo',
    'location', 'pot_size', 'health_status', 'last_fertilizing_date',
    'fertilizing_frequency', 'last_repotting_date', 'next_repotting_date',
    'growth_speed', 'max_height', 'is_toxic', 'flowering_season', 'difficulty_level',
    'is_indoor', 'is_outdoor', 'is_favorite', 'is_archived', 'archived_date', 'archived_reason'
];
```

---

## 8. Résumé

- Génère le modèle et la migration
- Édite la migration avec tous les champs nécessaires
- Crée la table `categories`
- Lance les migrations
- Vérifie la création dans la base
- Mets à jour le modèle Plant

---

**Tu as maintenant une table `plants` complète et prête à l’emploi dans Laravel !**