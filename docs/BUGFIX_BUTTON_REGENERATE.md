# 🔧 Fix: Bouton "Régénérer" référence

**Date:** 22 octobre 2025
**Branche:** v1.13
**Issue:** Le bouton "Régénérer" dans la page d'édition ne fonctionne pas

## 📋 Problème identifié

### Avant le correctif
- ❌ Clic sur le bouton "🔄 Régénérer" dans edit plant
- ❌ Rien ne se passe
- ❌ Pas d'erreur visible dans la console, mais la fonction ne reçoit pas le bouton correctement

### Cause racine
La fonction JavaScript `regenerateReference()` utilisait `event.target` pour récupérer le bouton :

```javascript
window.regenerateReference = function() {
  // ...
  const btn = event.target;  // ❌ PROBLÈME!
```

Quand on appelle la fonction via `onclick="regenerateReference()"`, l'objet `event` n'est pas défini ou ne pointe pas correctement vers le bouton.

## 🔨 Solution implémentée

### 1. Passer le bouton en paramètre (ligne 108)
**AVANT:**
```html
<button type="button" onclick="regenerateReference()">
```

**APRÈS:**
```html
<button type="button" onclick="regenerateReference(this)">
```

Le mot-clé `this` passe la référence du bouton directement à la fonction.

### 2. Mettre à jour la fonction JavaScript (ligne 305-354)
**AVANT:**
```javascript
window.regenerateReference = function() {
  const btn = event.target;  // ❌ Fragile
```

**APRÈS:**
```javascript
window.regenerateReference = function(btn) {
  if (!btn) {
    console.error('Button element not found');
    return;
  }
```

Maintenant :
- ✅ Le bouton est passé en paramètre
- ✅ Vérification que le bouton est défini
- ✅ Utilisation directe de `btn` au lieu de `event.target`

## ✅ Flux corrigé

### Avant (❌ ne fonctionnait pas)
```
1. Clic sur bouton
2. onclick="regenerateReference()"
3. Fonction appelée SANS paramètre
4. event.target = undefined/incorrect
5. Erreur silencieuse, rien ne se passe
```

### Après (✅ fonctionne)
```
1. Clic sur bouton
2. onclick="regenerateReference(this)"
3. Fonction appelée AVEC le bouton
4. btn = élément du bouton
5. Vérification que btn existe
6. API appelée, référence générée
7. Bouton passe au vert "✓ Référence générée!"
```

## 🎯 Fonctionnalité correcte

### Ce qui fonctionne maintenant

1. **Clic sur "Régénérer"**
   - Bouton devient "⏳ Génération..."
   - Appelle l'API `/plants/generate-reference`

2. **API génère la référence**
   - Récupère la famille du formulaire
   - Génère le numéro séquentiel
   - Retourne la référence au format `FAMILLE-NNN`

3. **Réponse affichée**
   - Champ référence mis à jour
   - Bouton devient vert "✓ Référence générée!"
   - Après 2 secondes, revient au gris "🔄 Régénérer"

### Format généré
- Format: `FAMILLE-NNN`
- Exemple: `ORCHI-001`, `CACTA-002`, etc.
- Incrémente automatiquement pour chaque famille

## 🧪 Test

### Via interface web
1. Aller à `/plants/{id}/edit`
2. S'assurer que "Famille" est remplie
3. Cliquer sur le bouton "🔄 Régénérer"
4. ✅ Le champ "Référence" doit se remplir automatiquement
5. ✅ Le bouton devient vert temporairement

### Cas d'erreur
- **Pas de famille remplie** → Message d'alerte: "Veuillez d'abord remplir le champ 'Famille'"
- **Erreur API** → Message d'erreur affichée
- **Problème réseau** → Message "Erreur lors de la génération"

## 📝 Notes techniques

- **Modification:** Passage du bouton en paramètre de fonction
- **Fichier:** `resources/views/components/plant-form.blade.php`
- **Ligne onclick:** 108
- **Fonction:** ligne 305-354
- **API endpoint:** `POST /plants/generate-reference`
- **Contrôleur:** `PlantController::generateReferenceAPI()`

## 🚀 Commande commit
```
git commit -m "Fix: Bouton \"Régénérer\" référence - Passer le bouton en paramètre"
```

## ✨ Résumé

| Aspect | Avant | Après |
|--------|-------|-------|
| **Bouton cliquable** | ❌ Non | ✅ Oui |
| **Référence générée** | ❌ Non | ✅ Oui |
| **Feedback visuel** | ❌ Aucun | ✅ Oui (vert) |
| **Paramètre** | ❌ event.target | ✅ this (explicite) |
| **Robustesse** | ⚠️ Fragile | ✅ Vérifiée |
