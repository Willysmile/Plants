# Fix: Missing Plant Histories Views

## Problème
Lorsque l'utilisateur tentait de créer une fertilisation via la modale plants, il recevait l'erreur :
```
View [plants.histories.index] not found.
```

## Root Cause
Le contrôleur `PlantHistoryController` cherchait à afficher des vues qui n'existaient pas :
- `plants.histories.index` 
- `plants.histories.show`
- `plants.histories.edit`

Ces vues n'avaient jamais été créées dans le répertoire `resources/views/plants/histories/`.

## Solution
Créé trois fichiers de vue pour gérer les "Infos Diverses" (plant histories) :

### 1. `resources/views/plants/histories/index.blade.php`
- Affiche la liste paginée des infos diverses pour une plante
- Formulaire pour ajouter une nouvelle info
- Boutons pour éditer/supprimer chaque info

### 2. `resources/views/plants/histories/show.blade.php`
- Affiche les détails d'une info diverseindividuelle
- Boutons pour éditer ou supprimer
- Lien de retour

### 3. `resources/views/plants/histories/edit.blade.php`
- Formulaire pour éditer une info diverse existante
- Validation avec limite de 144 caractères
- Validation côté serveur dans `PlantHistoryController`

## Routes Concernées
```php
Route::resource('plants.histories', PlantHistoryController::class);
```

Cette route resource génère automatiquement les actions :
- `GET /plants/{plant}/histories` → `index()`
- `POST /plants/{plant}/histories` → `store()`
- `GET /plants/{plant}/histories/{history}` → `show()`
- `GET /plants/{plant}/histories/{history}/edit` → `edit()`
- `PUT/PATCH /plants/{plant}/histories/{history}` → `update()`
- `DELETE /plants/{plant}/histories/{history}` → `destroy()`

## Références
- PlantHistoryController: `app/Http/Controllers/PlantHistoryController.php`
- PlantHistory Model: `app/Models/PlantHistory.php`
- Plant Model relation: `public function histories()`
- Route resource: `routes/web.php` ligne 55

## Commit
```
5d09523 (HEAD -> v1.14) feat: create plant histories views (index, show, edit)
```

## Vérification
Toutes les vues sont maintenant présentes et l'erreur devrait être résolue.
