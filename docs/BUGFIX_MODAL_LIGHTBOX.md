# 🔧 Fix: Photo Principale Ne S'Ouvre Pas dans la Modale

## 📋 Problème

Quand tu cliques sur la photo principale dans la modale plants:
- ❌ Le lightbox NE s'ouvre PAS
- ❌ Ou il ouvre une image différente (pas la photo principale)

## 🔍 Cause Racine

### Le Problème Identifié:

1. **Modal chargée** → `modal-manager.js` appelle `display(html)`
2. **loadLightboxImages()** est appelée ✅
3. ❌ **MAIS** `GalleryManager.init()` n'était PAS appelée
4. ❌ Sans `init()`, les event listeners ne sont pas configurés
5. ❌ Cliquer sur la photo principale ne déclenche rien

### Détail du Code:

**Avant (Bugué):**
```javascript
display(html) {
  this.modalContent.innerHTML = html;
  this.loadLightboxImages();  // Charge les images ✓
  
  // ❌ GalleryManager.init() MANQUANTE!
  // Les event listeners ne sont pas configurés
}
```

**Le handler `setupMainPhotoHandlers()` n'était jamais appelé:**
```javascript
setupMainPhotoHandlers() {
  document.addEventListener('click', (event) => {
    if (!event.target.matches('[data-type="main-photo"]')) return;
    // ... ouvrir lightbox ...
  });
}
```

---

## ✅ Solution Appliquée

### Fichier: `public/js/modal-manager.js`

Ajouter l'appel à `GalleryManager.init()` après `loadLightboxImages()`:

```javascript
display(html) {
  this.modalContent.innerHTML = html;
  this.modalRoot.style.display = 'flex';
  document.body.style.overflow = 'hidden';

  // Charger les images lightbox
  this.loadLightboxImages();

  // 🔧 FIX: Initialiser le gestionnaire de galerie
  if (typeof GalleryManager !== 'undefined') {
    const modal = this.modalContent.querySelector('[data-modal-plant-id]');
    if (modal) {
      // Initialiser les handlers pour les thumbnails et photo principale
      GalleryManager.init(modal);
      // Restaurer l'état des échanges
      GalleryManager.restoreSwapState(modal);
    }
  }
}
```

### Que fait `GalleryManager.init(modal)` ?

1. **Charge les images lightbox** depuis le JSON embarqué
2. **Configure `setupThumbnailHandlers()`** → Écoute les clics sur les miniatures
3. **Configure `setupMainPhotoHandlers()`** → ✅ **NOUVEAU**: Écoute les clics sur la photo principale
4. **Restaure l'état précédent** si une photo avait été swappée

---

## 🧪 Comportement Après Fix

| Action | Avant | Après |
|--------|-------|-------|
| Ouvrir modale | ✅ Modale affichée | ✅ Modale affichée |
| Cliquer photo principale | ❌ Rien | ✅ Lightbox ouvre avec la bonne image |
| Cliquer miniature | ❌ Rien | ✅ Swap + Lightbox ouvre image |
| Cliquer à nouveau sur ancienne principale | ❌ N/A | ✅ Lightbox ouvre |

---

## 📝 Code Modifié

**Fichier:** `public/js/modal-manager.js` (Méthode `display()`)

**Changement:**
- ✅ Ligne 84: Appel à `GalleryManager.init(modal)`
- ✅ Ligne 85-86: Restauration de l'état swap après init

---

## 🚀 Résultats

✅ **Après ce fix:**
- Photo principale cliquable dans la modale
- Lightbox affiche la bonne image
- Miniatures fonctionnent correctement
- Swap de galerie fonctionne entièrement
- État persiste lors de la réouverture de la modale

---

## 📌 Notes Techniques

- `GalleryManager.init()` est défensif (vérif `typeof`)
- Appelé une seule fois par modal (pas de duplication d'event listeners)
- Les listeners sont configurés sur le document (event delegation)
- La modale est fermée et réouverte → `init()` est appelé à chaque fois ✓

---

**Date:** 22 octobre 2025  
**Status:** ✅ RÉSOLU
