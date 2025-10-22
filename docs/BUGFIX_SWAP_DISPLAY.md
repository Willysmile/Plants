# 🔧 Fix: Ancienne photo principale n'apparaît pas après swap

**Date:** 22 octobre 2025
**Branche:** v1.13
**Issue:** Quand on clique sur une miniature pour la swapper avec la photo principale, la miniature devient la photo principale, MAIS l'ancienne photo principale n'apparaît pas comme miniature.

## 📋 Problème identifié

### Avant le correctif
1. ✅ Clic sur miniature → devient photo principale
2. ❌ Ancienne photo principale → **disparaît** (n'apparaît nulle part)
3. Les autres miniatures restent inchangées

### Cause racine
La logique du template Blade filtre pour **exclure** la photo principale de la galerie affichée :

```php
$gallery = $plant->photos->filter(function($p) use ($plant){
    if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
    return true;
})->values();
```

Après un swap JavaScript :
- La base de données n'a **pas changé** (on ne fait que modifier le DOM)
- La logique Blade continue de filtrer la même photo principale
- L'ancienne photo principale n'est donc jamais affichée comme miniature

## 🔨 Solution implémentée

### Modifications à `public/js/gallery-manager.js`

#### 1. **Sauvegarder l'ancienne source AVANT le swap** (ligne 108-110)
```javascript
// Sauvegarder les sources AVANT le swap
const mainOriginalSrc = mainPhoto.getAttribute('data-original-src') || mainPhoto.src;
const oldMainPhotoSrc = mainPhoto.src; // ← NOUVEAU: Sauvegarder pour updateGalleryThumbnails
```

#### 2. **Passer l'ancienne source à updateGalleryThumbnails** (ligne 118)
```javascript
// AVANT: this.updateGalleryThumbnails(modal, thumbnailBtn, mainPhoto);
// APRÈS:
this.updateGalleryThumbnails(modal, thumbnailBtn, mainPhoto, oldMainPhotoSrc);
```

#### 3. **Nouvelle logique de updateGalleryThumbnails** (ligne 210-250)
```javascript
updateGalleryThumbnails(modal, swappedThumb, mainPhoto, oldMainPhotoSrc) {
  // 1. Cloner la miniature cliquée
  const newThumbBtn = swappedThumb.cloneNode(true);
  
  // 2. Mettre à jour pour afficher l'ancienne photo principale
  newThumbBtn.setAttribute('data-original-src', oldMainPhotoSrc);
  const newImg = newThumbBtn.querySelector('img');
  if (newImg) {
    newImg.src = oldMainPhotoSrc;
  }
  
  // 3. Remplacer l'ancienne miniature → elle affiche maintenant l'ancienne principale
  swappedThumb.replaceWith(newThumbBtn);
  
  // 4. Réinitialiser les event listeners
  this.setupThumbnailHandlers(modal);
}
```

#### 4. **Restauration améliorée** (ligne 345-385)
La fonction `_restoreSnapshot()` restaure maintenant aussi le DOM des miniatures :

```javascript
_restoreSnapshot(modal, plantId) {
  // ...
  // Restaurer la miniature cliquée (elle a été remplacée après le swap)
  if (currentThumbBtn) {
    const restoredThumbBtn = currentThumbBtn.cloneNode(true);
    restoredThumbBtn.setAttribute('data-original-src', snapshot.thumbOriginalSrc);
    const restoredImg = restoredThumbBtn.querySelector('img');
    if (restoredImg) {
      restoredImg.src = snapshot.thumbOriginalSrc;
    }
    currentThumbBtn.replaceWith(restoredThumbBtn);
    this.setupThumbnailHandlers(modal);
  }
  // ...
}
```

## ✅ Flux de swap amélioré

### Avant swap
```
Photo principale: Photo_A
Miniatures: [Photo_B, Photo_C, Photo_D]
```

### Utilisateur clique sur Photo_B
```
Photo principale: Photo_B (nouvelle)
Miniatures: [Photo_A (ancienne), Photo_C, Photo_D]
         ↑ Maintenant visuelle à la place de Photo_B!
```

### Clic sur miniature pour restaurer
```
Photo principale: Photo_A (restaurée)
Miniatures: [Photo_B (restaurée), Photo_C, Photo_D]
```

## 🧪 Cas d'usage

### Cas 1: Swap simple
1. Clic sur miniature 1 → devient principale
2. Ancienne principale apparaît à la place de la miniature
3. ✅ L'ancienne principale est cliquable et peut être cliquée pour restaurer

### Cas 2: Double swap
1. Clic sur miniature 1 → swap 1
2. Clic sur miniature 2 → restaure d'abord, puis swap 2
3. ✅ Miniature 1 revient à sa source originale
4. ✅ Miniature 2 affiche l'ancienne principale

### Cas 3: Fermeture et réouverture modale
1. Swap effectué
2. Fermeture modale
3. Réouverture modale de la même plante
4. ✅ Swap persiste grâce à `swapStates` et `restoreSwapState()`

## 🎯 Validation

### À tester manuellement
- [ ] Clic sur miniature → ancienne principale apparaît visuellement
- [ ] L'ancienne principale est cliquable (ouvre la galerie)
- [ ] Double swap: restaure correctement
- [ ] Clic sur ancienne principale pour restaurer fonctionne
- [ ] État persiste après fermeture/réouverture modale
- [ ] Multiple miniatures: chacune peut être swappée

### Résultat attendu
Après swap: **L'ancienne photo principale disparaît ET réapparaît immédiatement comme miniature à la place de celle cliquée.**

## 📝 Notes techniques

- **Snapshot complet**: Sauvegarde `mainOriginalSrc`, `thumbOriginalSrc`, `lightboxOriginal`, et maintenant `oldMainPhotoSrc`
- **DOM replacement**: Clone + remplacement force la réapplication des événements via `setupThumbnailHandlers()`
- **Prévention oublis**: Restaure aussi l'opacité et autres styles appliqués après swap
- **Sécurité**: Tous les clones maintiennent les `data-*` attributes

## 🚀 Commande commit
```
git commit -m "Fix: Afficher l'ancienne photo principale comme miniature après swap"
```
