# 🐛 Fiches techniques - Bugs corrigés v1.13

## BUGFIX #1: Images n'apparaissent pas

| Info | Détail |
|------|--------|
| **Priorité** | 🔴 CRITIQUE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: Corriger ImageService convertToWebp` |
| **Fichier** | `app/Services/ImageService.php` |

**Problème:**
- Images JPG ne s'affichaient pas après conversion WebP
- 40 plantes sans photo principale
- Symlink storage/public non fonctionnel

**Causes:**
1. `convertToWebp()` sauvegardait en chemin fixe (`photos/`)
2. `main_photo` field NULL pour 40 plantes
3. 132 JPG jamais convertis

**Solutions:**
1. Dynamique chemin dans ImageService
2. Créer commandes Artisan pour migration
3. Vérifier symlink

**Impact:** Images now affichées, ~55% réduction taille

---

## BUGFIX #2: Galerie swap - Ancienn photo disparaît

| Info | Détail |
|------|--------|
| **Priorité** | 🟠 HAUTE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: Ajouter updateGalleryThumbnails` |
| **Fichier** | `public/js/gallery-manager.js` |

**Problème:**
- Clic miniature → Devient principale
- Ancienne principale disparaît

**Cause:**
- Template Blade filtre `main_photo` des miniatures
- DOM ne se met pas à jour après swap JS

**Solution:**
- `updateGalleryThumbnails()` (ligne 205-250)
- Clone et remplace DOM miniature

**Impact:** Galerie visible complètement

---

## BUGFIX #3: Photo non-cliquable en modal

| Info | Détail |
|------|--------|
| **Priorité** | 🟠 HAUTE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: Initialiser GalleryManager` |
| **Fichier** | `public/js/modal-manager.js` |

**Problème:**
- Photo principale en modal non-cliquable
- Lightbox n'ouvre pas

**Cause:**
- `GalleryManager.init()` jamais appelé en modal
- Event listeners pas configurés

**Solution:**
- Appeler `init()` dans `display()` (ligne 84-88)

**Impact:** Lightbox fonctionne en modal

---

## BUGFIX #4: Auto-génération référence

| Info | Détail |
|------|--------|
| **Priorité** | 🟡 MOYENNE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: Auto-générer la référence` |
| **Fichier** | `app/Models/Plant.php` |

**Problème:**
- Création plante → référence reste vide
- Pas auto-généré

**Cause:**
- `generateReference()` existe mais jamais appelé
- Pas d'event `creating`

**Solution:**
- Ajouter `booted()` (ligne 319-334)
- Event `creating` génère si vide

**Impact:** Références toujours uniques

---

## BUGFIX #5: Bouton "Régénérer"

| Info | Détail |
|------|--------|
| **Priorité** | 🟡 MOYENNE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: Bouton Régénérer` |
| **Fichier** | `resources/views/components/plant-form.blade.php` |

**Problème:**
- Clic bouton → Rien ne se passe

**Cause:**
- `event.target` non défini
- Pas de paramètre passé

**Solution:**
- `onclick="regenerateReference(this)"`
- Fonction accepte paramètre `btn`

**Impact:** Bouton fonctionne

---

## BUGFIX #6: Doublons références

| Info | Détail |
|------|--------|
| **Priorité** | 🔴 CRITIQUE |
| **Date** | 22 oct 2025 |
| **Commits** | 2 commits |
| **Fichiers** | `Plant.php`, `PlantController.php` |

**Problème:**
- `SQLSTATE[23000]: Duplicate entry`
- Création plante échoue

**Causes:**
1. Logique MAX défectueuse (ORDER BY DESC fragile)
2. Soft-deleted ignorés (mais constraint les inclut!)

**Solutions:**
- Commit 1: Utiliser `max()` au lieu de `orderBy`
- Commit 2: Ajouter `withTrashed()`

**Impact:** Zéro collisions garanties

---

## BUGFIX #7: Scripts composants

| Info | Détail |
|------|--------|
| **Priorité** | 🔴 CRITIQUE |
| **Date** | 22 oct 2025 |
| **Commit** | `Fix: @stack('scripts')` |
| **Fichier** | `resources/views/layouts/app.blade.php` |

**Problème:**
- `ReferenceError: regenerateReference is not defined`

**Cause:**
- `@push('scripts')` dans composant
- Pas de `@stack('scripts')` dans layout
- Scripts jamais affichés!

**Solution:**
- Ajouter `@stack('scripts')` avant `</body>`

**Impact:** Tous scripts composants chargés

---

## 📈 Résumé statistiques

```
Bugs corrigés:    7
Fichiers modif:   9
Fichiers créés:   6
Commits:          9

Images converties: 132 JPG → WebP
Plantes migrées:   40 (main_photo)
Réduction taille:  ~55%
```

---

## ✅ Validation

- [x] Tous les bugs testés
- [x] Pas de régression
- [x] Code committé
- [x] Documentation complète
