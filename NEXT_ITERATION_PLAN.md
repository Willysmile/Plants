# Prochaine Itération: Test & Affichage des Relations

**Date Prévue:** 23-24 octobre 2025

---

## 📋 Agenda

### Phase 1: Vérification des Formulaires (15 min)
- [ ] Démarrer le serveur Laravel
- [ ] Aller à `http://localhost:8001/plants/101/edit`
- [ ] Vérifier que les selects "Emplacement actuel" et "Lieu d'achat" s'affichent
- [ ] Vérifier que les options se remplissent avec les locations et purchase_places

### Phase 2: Tests de Création (20 min)
- [ ] Aller à `http://localhost:8001/plants/create`
- [ ] Créer une nouvelle plante avec:
  - Nom: "Test FK Select Plant"
  - Famille: "Araceae"
  - Emplacement: Sélectionner "Fenêtre salon" (ou créer un lieu)
  - Lieu d'achat: Sélectionner "Pépinière Local" (ou créer un lieu)
- [ ] Soumettre le formulaire
- [ ] Vérifier que la plante est créée avec `location_id` et `purchase_place_id` remplis
- [ ] Via tinker: `Plant::with('location','purchasePlace')->latest()->first()`

### Phase 3: Tests d'Édition (15 min)
- [ ] Aller à `http://localhost:8001/plants/101/edit`
- [ ] Changer l'emplacement
- [ ] Changer le lieu d'achat
- [ ] Soumettre
- [ ] Vérifier les changements en DB

### Phase 4: Amélioration de l'Affichage (30 min)
- [ ] Mettre à jour `resources/views/plants/show.blade.php`:
  - Afficher le nom de l'emplacement via `$plant->location->name`
  - Afficher le nom du lieu d'achat via `$plant->purchasePlace->name`
  - Afficher les conditions de l'emplacement (light_level, humidity, temp)
  - Afficher les infos du lieu d'achat (address, phone, website)

### Phase 5: Création de Seeds (Optionnel - 20 min)
```bash
php artisan make:seeder LocationSeeder
php artisan make:seeder PurchasePlaceSeeder
php artisan db:seed --class=LocationSeeder
php artisan db:seed --class=PurchasePlaceSeeder
```

- [ ] LocationSeeder: 5-10 emplacements typiques
- [ ] PurchasePlaceSeeder: 5-10 lieux d'achat typiques
- [ ] Appeler depuis DatabaseSeeder

---

## 🎯 Objectifs de cette itération

✅ **Primary:** Valider que les FK selects fonctionnent correctement  
✅ **Secondary:** Afficher les relations dans la page show  
✅ **Tertiary:** Pré-remplir des données via seeds  

---

## 📊 Critères d'acceptation

- [x] Formulaires create/edit affichent les selects
- [x] Créer une plante sauvegarde location_id et purchase_place_id
- [x] Éditer une plante met à jour les FK
- [x] Page show affiche le nom de l'emplacement et du lieu
- [x] Pas d'erreurs de validation

---

## 🔧 Commandes utiles

```bash
# Tester les relations
php artisan tinker
> $plant = Plant::with('location','purchasePlace')->find(101);
> $plant->location->name;
> $plant->purchasePlace->name;

# Vider les caches
php artisan view:clear && php artisan config:clear

# Redémarrer le serveur
php artisan serve --port=8001

# Regarder les logs
tail -f storage/logs/laravel.log
```

---

## 🎨 Améliorations d'affichage prévues

### plants/show.blade.php - Section Informations

```blade
<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
  <!-- Emplacement actuel -->
  <div class="bg-blue-50 p-4 rounded">
    <h4 class="font-semibold text-blue-900">📍 Emplacement</h4>
    @if($plant->location)
      <p class="text-sm">{{ $plant->location->name }}</p>
      <p class="text-xs text-gray-600">
        💡 {{ $plant->location->light_level ?? 'N/A' }} |
        💧 {{ $plant->location->humidity_level ?? 'N/A' }}% |
        🌡️ {{ $plant->location->temperature ?? 'N/A' }}°C
      </p>
    @else
      <p class="text-sm text-gray-500">Non défini</p>
    @endif
  </div>

  <!-- Lieu d'achat -->
  <div class="bg-purple-50 p-4 rounded">
    <h4 class="font-semibold text-purple-900">🛒 Lieu d'achat</h4>
    @if($plant->purchasePlace)
      <p class="text-sm">{{ $plant->purchasePlace->name }}</p>
      <p class="text-xs text-gray-600">
        {{ $plant->purchasePlace->address ?? '' }}
        {{ $plant->purchasePlace->phone ? '| ' . $plant->purchasePlace->phone : '' }}
      </p>
    @else
      <p class="text-sm text-gray-500">Non défini</p>
    @endif
  </div>
</div>
```

---

## 🚨 Possibles blocages

| Blocage | Mitigation |
|---------|-----------|
| Base de données vide | Créer au moins 2 locations et 2 purchase_places via l'interface |
| Erreur de validation FK | Vérifier que les IDs existent dans les tables locations et purchase_places |
| Formulaire ne sauvegarde pas | Vérifier les logs: `tail -f storage/logs/laravel.log` |

---

## 📈 Métriques de succès

- ✅ 0 erreur SQL
- ✅ Tous les selects s'affichent
- ✅ Création/Édition fonctionnent
- ✅ Les relations s'affichent en show

---

**Statut:** À commencer ⏳
