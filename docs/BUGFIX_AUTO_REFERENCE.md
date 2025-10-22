# 🔧 Fix: Auto-génération de la référence

**Date:** 22 octobre 2025
**Branche:** v1.13
**Issue:** L'auto-génération de la référence ne fonctionne plus lors de la création d'une plante

## 📋 Problème identifié

### Avant le correctif
- ❌ Création d'une nouvelle plante sans remplir le champ "Référence"
- ❌ La référence reste vide → n'est pas auto-générée
- ❌ Il faut remplir le champ manuellement ou appeler l'API séparément

### Cause racine
Le modèle `Plant` **n'avait pas d'event `creating`** pour générer automatiquement la référence.

- La méthode `generateReference()` existait mais était jamais appelée automatiquement
- L'API `generateReferenceAPI()` existe mais nécessite un appel manuel JavaScript
- Lors de la création via formulaire, aucun code ne déclenchait la génération

## 🔨 Solution implémentée

### Ajout du "boot" du modèle dans `app/Models/Plant.php` (ligne 319-334)

```php
protected static function booted(): void
{
    static::creating(function ($model) {
        // Si aucune référence n'est fournie, la générer automatiquement
        if (empty($model->reference) && !empty($model->family)) {
            $model->reference = $model->generateReference();
        }
    });
}
```

### Logique

1. **Quand ?** Lors de l'événement `creating` (avant insertion en DB)
2. **Condition ?** 
   - `empty($model->reference)` = Champ référence vide OU null
   - `!empty($model->family)` = La famille est fournie (sinon impossible de générer)
3. **Action ?** Appeler `$model->generateReference()` qui retourne une référence formatée

### Format généré

**Format:** `FAMILLE-NNN` où :
- `FAMILLE` = 5 premières lettres de la famille en majuscules
- `NNN` = Numéro séquentiel (001, 002, etc.)

**Exemples:**
- Orchidée (Orchidaceae) → `ORCHI-001`, `ORCHI-002`, etc.
- Cactée (Cactaceae) → `CACTA-001`, `CACTA-002`, etc.
- Araceae → `ARACE-001`, `ARACE-002`, etc.

## ✅ Flux après correctif

### Création de plante
```
1. Utilisateur remplit le formulaire (name, family, etc.)
2. Soumission du formulaire (store)
3. Validation requête
4. Plant::create($data)
5. Event 'creating' déclenché ✨
6. Si family est fournie ET reference est vide
   → reference = auto-générée
7. Plante créée avec référence auto-générée
```

### Exemple concret
```
Formulaire:
- Nom: "Phalaenopsis roses"
- Famille: "Orchidaceae"
- Référence: [vide]

Après save:
- reference = "ORCHI-001" (généré automatiquement)
```

## 🎯 Cas d'usage

### Cas 1: Référence vide + Famille fournie
✅ **Référence générée automatiquement**

### Cas 2: Référence fournie
✅ **Utilise la référence fournie** (évite la génération)

### Cas 3: Pas de famille + Référence vide
✅ **Pas de génération** (condition `!empty($model->family)` échoue)
- Alternative: Si besoin, utiliser l'API `generateReferenceAPI`

### Cas 4: Mise à jour d'une plante existante
✅ **L'event `creating` ne s'active pas** (c'est `updating` qui s'activerait)
- Les plantes existantes gardent leur référence inchangée

## 🧪 Test manuel

### Via Tinker
```bash
php artisan tinker

# Cas 1: Auto-génération
$plant = \App\Models\Plant::create([
    'name' => 'Test Plant',
    'family' => 'Araceae',
    'watering_frequency' => 3,
    'light_requirement' => 3,
]);
echo $plant->reference; // Devrait afficher: ARACE-001

# Cas 2: Référence fournie (pas de génération)
$plant2 = \App\Models\Plant::create([
    'name' => 'Test Plant 2',
    'family' => 'Araceae',
    'reference' => 'CUSTOM-REF',
    'watering_frequency' => 3,
    'light_requirement' => 3,
]);
echo $plant2->reference; // Devrait afficher: CUSTOM-REF
```

### Via interface web
1. Aller à `/plants/create`
2. Remplir le formulaire (name, family, etc.)
3. **Ne pas remplir le champ "Référence"**
4. Soumettre
5. ✅ La plante est créée avec une référence auto-générée

## 📝 Notes techniques

- **Event utilisé:** `creating` (avant insertion)
- **Modèle:** `Plant` (app/Models/Plant.php)
- **Condition:** Vérification que family + reference vide
- **Idempotent:** Si référence est fournie, elle n'est pas écrasée
- **Impact:** Zéro changement côté database ou migrations
- **Backward compatible:** Les créations manuelles avec référence continuent de fonctionner

## 🚀 Commande commit
```
git commit -m "Fix: Auto-générer la référence lors de la création d'une plante"
```

## ✨ Améliorations futures possibles

- Ajouter option pour changer le préfixe de la référence (ex: custom prefix au lieu de family)
- Ajouter hook pour `updating` si on veut permettre la régénération
- Ajouter logging de la génération pour audit trail
