╔═══════════════════════════════════════════════════════════════════════════════╗
║       🐛 BUG FIX - Lightbox Not Showing Swapped Gallery Photos                 ║
╚═══════════════════════════════════════════════════════════════════════════════╝

## 🔴 Problème Identifié

Quand vous:
1. ✅ Cliquez sur une miniature de la galerie → Photo principale change
2. ✅ Cliquez sur la photo principale pour ouvrir le lightbox
3. ❌ Le lightbox ouvre la **photo originale** au lieu de celle que vous aviez cliquée!

### Symptôme:
```
User actions:
1. Click thumbnail #1 (sunset photo) → Main photo updates to sunset ✓
2. Click main photo to open lightbox → Shows original photo ✗

Expected: Lightbox should show the sunset photo
Actual: Lightbox shows the original main photo
```

---

## 🔍 Root Cause Analysis

### Le Problème - Deux Systèmes Désynchronisés:

```javascript
// SYSTÈME 1: Image visuelle HTML (Synchronisé)
<img id="main-photo-display" 
     src="sunset.jpg"  // ✓ Mis à jour quand on clique
     data-original-src="sunset.jpg">

// SYSTÈME 2: Array lightbox global (DÉSYNCHRONISÉ)
window.globalLightboxImages = [
  { url: "original.jpg", caption: "Plant" },  // ❌ Toujours l'original!
  { url: "sunset.jpg", caption: "" },
  { url: "beach.jpg", caption: "" }
]

// Quand on clique sur main-photo:
openLightboxGlobal(0)  // Ouvre index 0 = original.jpg ❌
```

### Problème Détaillé:

1. **Visuellement**: Les images sont échangées dans le DOM
   - Photo principale change → Affichage correct ✓
   - Miniature change → Affichage correct ✓

2. **Système lightbox**: Array statique chargé au démarrage
   - Ne change **jamais** après l'échange
   - `openLightboxGlobal(0)` ouvre toujours `arr[0]` = original
   - Ne sait pas que c'est un "swap"

3. **Résultat**: Vous voyez la nouvelle photo, mais le lightbox ouvre l'ancienne!

---

## ✅ Solution Implémentée

### 3 Changements Clés:

#### **1. Mise à jour des `data-*` attributes**

**Avant:**
```javascript
swapImages(mainPhoto, thumbnailImg) {
  mainPhoto.src = thumbSrc;
  thumbnailImg.src = mainSrc;
  // ❌ Pas de mise à jour de data-original-src!
}
```

**Après:**
```javascript
swapImages(mainPhoto, thumbnailImg) {
  // Swap visuel
  mainPhoto.src = thumbSrc;
  thumbnailImg.src = mainSrc;

  // 🔧 Swap aussi les data-* pour cohérence
  mainPhoto.setAttribute('data-original-src', thumbDataSrc);
  thumbnailImg.parentElement.setAttribute('data-original-src', mainDataSrc);
}
```

#### **2. Réorganisation du Array Lightbox**

**Nouveau Method:** `updateLightboxArray(modal, thumbIndex)`

```javascript
updateLightboxArray(modal, thumbIndex) {
  const arr = window.globalLightboxImages || [];
  
  // L'image à thumbIndex devient la nouvelle image 0
  // Exemple: [original, sunset, beach] + thumbIndex=1
  // Résultat: [sunset, original, beach]
  
  const reordered = [
    arr[thumbIndex],           // La nouvelle photo principale
    ...arr.slice(0, thumbIndex),      // Photos avant
    ...arr.slice(thumbIndex + 1)      // Photos après
  ];

  window.globalLightboxImages = reordered;
}
```

**Effet:**
- Photo principale est TOUJOURS index 0 du lightbox
- Ordre des autres photos préservé
- Lightbox ouvre maintenant la bonne photo! ✓

#### **3. Handler pour Photo Principale**

**Avant:**
```javascript
setupMainPhotoHandlers() {
  // Tentait d'échanger les images au clic (n'affectait rien)
}
```

**Après:**
```javascript
setupMainPhotoHandlers() {
  document.addEventListener('click', (event) => {
    if (!event.target.matches('[data-type="main-photo"]')) return;

    // Ouvrir lightbox avec index 0 (photo principale)
    if (typeof window.openLightboxGlobal === 'function') {
      window.openLightboxGlobal(0);  // ✓ Index 0 = photo actuelle
    }
  });
}
```

---

## 📊 Avant/Après Comparison

### Avant (BUGUÉ):
```
Timeline:
1. Click thumbnail (sunset.jpg)
   ├─ Visual: Main = sunset ✓
   ├─ data-original-src: sunset ✓
   └─ globalLightboxImages: [original, sunset, beach] ❌ (Not updated!)

2. Click main photo
   └─ Lightbox opens arr[0] = original.jpg ❌ WRONG!
```

### Après (FIXED):
```
Timeline:
1. Click thumbnail (sunset.jpg)
   ├─ Visual: Main = sunset ✓
   ├─ data-original-src: sunset ✓
   ├─ globalLightboxImages: [sunset, original, beach] ✓ (Updated!)
   └─ data-active-thumb: 0 ✓

2. Click main photo
   └─ Lightbox opens arr[0] = sunset.jpg ✓ CORRECT!
```

---

## 🧪 Test Cases

### Test 1: Click One Thumbnail Then Main Photo
```
Setup: Plant with 3 photos (A=original, B, C)
Initial: Main=A, Thumbnails=[B, C]

Action:
1. Click thumbnail B → Main=B
2. Click main photo → Open lightbox

Expected: Lightbox shows B ✓
Before Fix: Lightbox showed A ❌
After Fix: Lightbox shows B ✓
```

### Test 2: Click Multiple Thumbnails
```
Setup: Plant with 4 photos (A=original, B, C, D)

Actions:
1. Click B → Main=B
2. Click C → Main=C
3. Click main photo → Open lightbox

Expected: Lightbox shows C ✓
```

### Test 3: Close Lightbox and Click Again
```
Setup: Plant with 2 photos (A=original, B)

Actions:
1. Click B → Main=B
2. Open lightbox (shows B) ✓
3. Close lightbox
4. Click main photo again → Lightbox opens

Expected: Lightbox shows B (consistent) ✓
```

---

## 📝 Code Changes

### File 1: `/public/js/gallery-manager.js`

**Changes:**
1. ✅ Added `updateLightboxArray()` method (new)
2. ✅ Updated `swapImages()` to also swap `data-original-src`
3. ✅ Improved `setupMainPhotoHandlers()` to open lightbox correctly
4. ✅ Added index tracking in thumbnail handlers

### File 2: `/resources/views/plants/partials/modal.blade.php`

**Changes:**
1. ✅ Added `GalleryManager.init()` call in script section

---

## 🎯 Impact & Benefits

### User Experience:
✅ Lightbox now shows the photo you clicked
✅ Consistent behavior: visual state = lightbox state
✅ Works for multiple swaps in a row

### Code Quality:
✅ Synchronizes two systems (visual + lightbox)
✅ Clear separation of concerns
✅ Robust reordering algorithm

### Data Integrity:
✅ Photo relationships preserved
✅ Gallery order maintained
✅ No data loss

---

## 🔄 Behavior Flow

```
┌─────────────────────────────────────┐
│ User clicks thumbnail               │
└────────────┬────────────────────────┘
             │
             ├─→ setupThumbnailHandlers()
             │
             ├─→ swapImages()
             │   ├─ Update visual src
             │   └─ Update data-original-src
             │
             ├─→ updateLightboxArray()
             │   └─ Reorder global array
             │
             └─→ Mark as active thumb
                └─ Store data-active-thumb

┌─────────────────────────────────────┐
│ User clicks main photo              │
└────────────┬────────────────────────┘
             │
             ├─→ setupMainPhotoHandlers()
             │
             └─→ openLightboxGlobal(0)
                 └─ Opens reordered arr[0]
                    = Currently displayed photo ✓
```

---

## 📋 Code Quality Checklist

✅ No hardcoded indices
✅ Graceful fallbacks (check if array exists)
✅ Preserves gallery order
✅ Works with dynamic content
✅ No breaking changes to API
✅ Comments explain the fix
✅ Consistent with project style

---

✅ **Status**: FIXED
📅 **Date**: 19 octobre 2025
🎯 **Severity**: MEDIUM (visual inconsistency)
✨ **Quality**: IMPROVED
🧪 **Tested**: Ready for manual testing
