# 🔧 Fix: regenerateReference is not defined

**Date:** 22 octobre 2025
**Branche:** v1.13
**Issue:** `Uncaught ReferenceError: regenerateReference is not defined at HTMLButtonElement.onclick`

## 📋 Problème identifié

### Erreur rencontrée
```
edit:226 Uncaught ReferenceError: regenerateReference is not defined
    at HTMLButtonElement.onclick (edit:226:97)
```

### Cause racine
Le composant `plant-form.blade.php` définit la fonction JavaScript `regenerateReference()` en la poussant avec `@push('scripts')`. Cependant, le layout `app.blade.php` ne contient **pas** de `@stack('scripts')` pour afficher ces scripts!

**Flow défaillant:**
```
1. Composant plant-form charge
2. @push('scripts') → Fonction regenerateReference() enregistrée
3. Bouton cliqué → Appel onclick="regenerateReference(this)"
4. ❌ Fonction pas encore définie! 
5. ReferenceError
```

**Pourquoi?**
- Le composant pousse les scripts avec `@push('scripts')`
- Le layout ne les affiche JAMAIS car il n'a pas `@stack('scripts')`
- Les scripts restent en attente, jamais exécutés

### Comparaison

**Layout app.blade.php - AVANT:**
```html
<!-- Page-specific scripts -->
@yield('extra-scripts')  ← Seulement ça
</body>
</html>
```

**Layout app.blade.php - APRÈS:**
```html
<!-- Page-specific scripts -->
@yield('extra-scripts')
<!-- Component-specific scripts (pushed via @push('scripts')) -->
@stack('scripts')  ← ✅ AJOUT
</body>
</html>
```

## 🔨 Solution implémentée

### Changement unique dans `resources/views/layouts/app.blade.php`

**Ajout de `@stack('scripts')` à la fin du body** (avant la fermeture `</body>`):

```php
<!-- Page-specific scripts -->
@yield('extra-scripts')

<!-- Component-specific scripts (pushed via @push('scripts')) -->
@stack('scripts')
</body>
</html>
```

### Pourquoi ça fonctionne?

1. ✅ **Blade Stacks:** Système de Laravel pour gérer les sections dynamiques
   - `@push('scripts')` → Ajoute du contenu à la pile 'scripts'
   - `@stack('scripts')` → Affiche tout ce qui a été poussé

2. ✅ **Timing:** Les scripts sont affichés à la fin du HTML
   - Tous les éléments DOM sont chargés
   - Pas de race conditions

3. ✅ **Composabilité:** Chaque composant peut ajouter ses propres scripts
   - plant-form pousse ses scripts
   - Autre composant pourrait aussi en pousser
   - Tous affichés au même endroit

## ✅ Flux corrigé

### Avant (❌ scripts jamais chargés)
```
1. Composant pousse: @push('scripts')
2. Layout n'affiche rien: pas de @stack('scripts')
3. Scripts restent en attente
4. Bouton cliqué → ReferenceError
```

### Après (✅ scripts chargés et accessibles)
```
1. Composant pousse: @push('scripts')
2. Layout affiche: @stack('scripts')
3. Scripts exécutés
4. Bouton cliqué → Fonction appelée correctement
5. Référence générée ✅
```

## 🧪 Vérification

### Ce qui fonctionne maintenant

1. **Bouton "Régénérer" en edit** ✅
   - Clique sur bouton
   - Fonction `regenerateReference(this)` appelée
   - API génère la référence
   - Champ rempli

2. **Tous les scripts des composants** ✅
   - plant-form: regenerateReference()
   - Autres: modal, gallery, etc.

3. **Aucune ReferenceError** ✅

## 📝 Notes techniques

- **Système:** Blade Stacks (Laravel)
- **Timing:** Scripts chargés à la fin du HTML (avant `</body>`)
- **Priorité:** 
  1. `app.js` (global)
  2. Alpine.js
  3. Lucide icons
  4. Composant scripts (derniers)

- **Compatibilité:** Fonctionne avec tous les composants utilisant `@push('scripts')`

## 🚀 Commande commit
```bash
git commit -m "Fix: Charger les scripts des composants - Ajouter @stack('scripts')"
```

## ✨ Autres layouts affectés

À vérifier si d'autres layouts ont le même problème :
- `resources/views/layouts/simple.blade.php` ✅ (a déjà `@stack('scripts')`)
- Autres layouts custom?

## 🎯 Leçon apprise

Quand un composant utilise `@push()`, le layout correspondant DOIT avoir le `@stack()` correspondant, sinon les scripts/styles ne seront jamais affichés!

```php
// Composant
@push('scripts')
  // ... script
@endpush

// Layout DOIT avoir:
@stack('scripts')  // ← Crucial!
```
