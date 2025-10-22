# 🔧 Fix: Système de Swap de Galerie - Modale Plants

## 📋 Problème Identifié

Quand tu cliquais sur une miniature pour l'échanger avec la photo principale dans la modale :
1. ✅ L'échange fonctionnait visuellement
2. ❌ MAIS quand tu cliquais sur ce qui était l'ancienne photo principale (maintenant miniature), rien ne se passait
3. ❌ Impossible de faire un 2e swap

### Cause Racine

La modale n'affiche que les photos qui ne sont **pas** la photo principale. Le code PHP filtre :

```php
$gallery = $plant->photos->filter(function($p) use ($plant){
    if ($plant->main_photo && $p->filename === $plant->main_photo) return false;  // EXCLURE la principale
    return true;
})->values();
```

**Problème :** Après un swap JavaScript (côté client), les miniatures affichées ne reflétaient pas le changement. L'ancienne photo principale restait absente de la liste des miniatures.

---

## ✅ Solution Appliquée

### Nouvelle Fonction: `updateGalleryThumbnails()`

Après chaque swap, la fonction :
1. **Récupère tous les boutons miniatures actuels**
2. **Clone la miniature cliquée** (qui a été swappée)
3. **Remplace son image** par celle qui était la photo principale
4. **Remplace le bouton** dans le DOM
5. **Réinitialise les event listeners**

```javascript
updateGalleryThumbnails(modal, swappedThumb, mainPhoto) {
  // Récupérer tous les boutons miniatures
  const allThumbs = Array.from(
    galleryContainer.querySelectorAll('[data-type="thumbnail"]')
  );
  
  // Créer une nouvelle miniature avec l'ancienne photo principale
  const newThumbBtn = swappedThumb.cloneNode(true);
  newThumbBtn.setAttribute('data-original-src', mainPhoto.getAttribute('data-original-src'));
  newThumbBtn.querySelector('img').src = mainPhoto.src;
  
  // Remplacer en DOM
  swappedThumb.replaceWith(newThumbBtn);
  
  // Réinitialiser les listeners
  this.setupThumbnailHandlers(modal);
}
```

### Intégration dans le Flux de Swap

La fonction est appelée après chaque échange :

```javascript
// Échanger les images visuelles
this.swapImages(mainPhoto, thumbnailImg);

// Mettre à jour l'array lightbox
this.updateLightboxArray(modal, thumbIndex);

// 🔧 NOUVEAU: Mettre à jour les miniatures affichées
this.updateGalleryThumbnails(modal, thumbnailBtn, mainPhoto);
```

---

## 🧪 Comportement Attendu Après Fix

| Étape | Action | Résultat |
|-------|--------|---------|
| 1 | Tu as: Photo principale A, Miniature B | ✅ Galerie affiche B |
| 2 | Tu cliques sur B | ✅ A ↔ B (swap) |
| 3 | Maintenant: Photo principale B, Miniature A | ✅ **A s'affiche maintenant!** |
| 4 | Tu cliques sur A | ✅ B ↔ A (deswap) |
| 5 | Retour: Photo principale A, Miniature B | ✅ B réapparaît |

---

## 📝 Code Modifié

**Fichier:** `public/js/gallery-manager.js`

### Changement 1: Ajout de l'appel à `updateGalleryThumbnails()` (ligne 124)

```javascript
// Mettre à jour l'array lightbox global
this.updateLightboxArray(modal, thumbIndex);

// 🔧 NOUVEAU FIX: Mettre à jour les miniatures de la galerie après le swap
this.updateGalleryThumbnails(modal, thumbnailBtn, mainPhoto);

// Sauvegarder snapshot
this.swapStates[plantId] = {...};
```

### Changement 2: Nouvelle méthode `updateGalleryThumbnails()` (ligne 205)

45 lignes de code pour :
- Cloner la miniature swappée
- Remplacer son image par celle de la photo principale
- Remplacer le DOM
- Réinitialiser les listeners

---

## 🚀 Résultats

✅ Après un swap dans la modale, tu peux maintenant :
- Cliquer immédiatement sur l'ancienne photo principale (maintenant miniature)
- Faire plusieurs swaps successifs
- Déswapper facilement

✅ L'expérience utilisateur est maintenant complète :
- Visual feedback immédiat ✅
- Cliquable et réactif ✅
- Pas de "boutons fantômes" ✅

---

## 📌 Notes Techniques

- La fonction est défensive (try-catch) pour éviter les crash
- Les event listeners sont réinitialisés avec `setupThumbnailHandlers(modal)`
- Les attributs `data-index` et `data-lightbox-index` sont préservés
- Les attributs `data-original-src` sont aussi mis à jour

---

**Date:** 22 octobre 2025  
**Status:** ✅ RÉSOLU
