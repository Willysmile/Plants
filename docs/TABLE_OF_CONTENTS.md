# 📋 Table des matières - Documentation v1.13

## 📚 Navigation complète

### Entrée principale
- **[README.md](README.md)** - Vue d'ensemble du projet
- **[QUICK_START.md](QUICK_START.md)** - Commencer vite (par cas d'usage)
- **[PROJECT_STATUS.md](PROJECT_STATUS.md)** - 📊 Rapport complet du projet (v1.13)

### Vue générale
- **[CHANGELOG_SESSION_V113.md](CHANGELOG_SESSION_V113.md)** - Tous les changements de cette session
- **[BUGS_SUMMARY.md](BUGS_SUMMARY.md)** - Résumé des 7 bugs corrigés
- **[VISUAL_SUMMARY.md](VISUAL_SUMMARY.md)** - Vue visuelle avec diagrammes

### 📋 Spécifications & Cahiers des charges
- **[SPECIFICATIONS.md](SPECIFICATIONS.md)** - Index des spécifications
  - [SPECS_v1_01.md](SPECS_v1_01.md) - v1.01 (MVP)
  - [SPECS_v1_02.md](SPECS_v1_02.md) - v1.02 (UI/UX amélioration)

### Fiches détaillées par bug

#### 🔴 Bugs critiques
1. **[BUGFIX_IMAGE_DISPLAY.md](BUGFIX_IMAGE_DISPLAY.md)**
   - Conversion WebP, migration main_photo
   - Impact: Images affichées, -55% taille

2. **[BUGFIX_REFERENCE_DUPLICATE.md](BUGFIX_REFERENCE_DUPLICATE.md)**
   - Doublons de références (SQLSTATE[23000])
   - Impact: Références uniques garanties

3. **[BUGFIX_REGENERATEREFERENCE_UNDEFINED.md](BUGFIX_REGENERATEREFERENCE_UNDEFINED.md)**
   - Scripts des composants non chargés
   - Impact: Tous scripts chargés

#### 🟠 Bugs hauts
4. **[BUGFIX_GALLERY_SWAP_MODAL.md](BUGFIX_GALLERY_SWAP_MODAL.md)**
   - Swap de galerie + affichage miniatures
   - Impact: Galerie complète visible

5. **[BUGFIX_SWAP_DISPLAY.md](BUGFIX_SWAP_DISPLAY.md)**
   - Ancienne photo disparaît après swap
   - Impact: Affichage correct

6. **[BUGFIX_MODAL_LIGHTBOX.md](BUGFIX_MODAL_LIGHTBOX.md)**
   - Photo non-cliquable en modal
   - Impact: Lightbox fonctionne

#### 🟡 Bugs moyens
7. **[BUGFIX_AUTO_REFERENCE.md](BUGFIX_AUTO_REFERENCE.md)**
   - Auto-génération références
   - Impact: Références auto-créées

8. **[BUGFIX_BUTTON_REGENERATE.md](BUGFIX_BUTTON_REGENERATE.md)**
   - Bouton régénérer ne fonctionne pas
   - Impact: Bouton opérationnel

---

## 🗂️ Structure complète

```
/docs/
├── README.md
│   └── Documentation principale
│
├── CHANGELOG_SESSION_V113.md
│   └── Changelog détaillé avec commits
│
├── BUGS_SUMMARY.md
│   └── Tableau récapitulatif des 7 bugs
│
├── TABLE_OF_CONTENTS.md (ce fichier)
│
└── BUGFIX_*.md (8 fiches techniques)
    ├── BUGFIX_IMAGE_DISPLAY.md
    ├── BUGFIX_GALLERY_SWAP_MODAL.md
    ├── BUGFIX_SWAP_DISPLAY.md
    ├── BUGFIX_MODAL_LIGHTBOX.md
    ├── BUGFIX_AUTO_REFERENCE.md
    ├── BUGFIX_BUTTON_REGENERATE.md
    ├── BUGFIX_REFERENCE_DUPLICATE.md
    └── BUGFIX_REGENERATEREFERENCE_UNDEFINED.md
```

---

## 🎯 Par type de lecteur

### 👤 Je veux comprendre rapidement
1. Lire: **[README.md](README.md)** (5 min)
2. Lire: **[BUGS_SUMMARY.md](BUGS_SUMMARY.md)** (10 min)

### 👨‍💻 Je suis développeur
1. Lire: **[README.md](README.md)**
2. Consulter: **[CHANGELOG_SESSION_V113.md](CHANGELOG_SESSION_V113.md)**
3. Détails: Voir **BUGFIX_*.md** spécifiques

### 🔧 Je dois corriger un bug similaire
1. Trouver le bug dans **[BUGS_SUMMARY.md](BUGS_SUMMARY.md)**
2. Ouvrir la fiche **BUGFIX_*.md**
3. Copier la solution

### 🧪 Je dois valider les tests
1. Lire: **[CHANGELOG_SESSION_V113.md](CHANGELOG_SESSION_V113.md)**
2. Section: "Tests validés"

---

## 📊 Vue par métrique

### Par priorité
- 🔴 Critiques: 3 bugs (Image, Duplicate, Scripts)
- 🟠 Hauts: 3 bugs (Swap, Display, Modal)
- 🟡 Moyens: 2 bugs (Reference, Button)

### Par impact
- **Haute** (5): Image, Duplicate, Swap, Modal, Scripts
- **Moyenne** (2): Reference, Button

### Par complexité
- **Haute**: Duplicate (2 commits), Image (migrations)
- **Moyenne**: Swap (DOM), Gallery, Reference
- **Basse**: Button, Scripts

---

## 🔗 Liens rapides

| Besoin | Document |
|--------|----------|
| Comprendre globalement | [README.md](README.md) |
| Voir tous les changements | [CHANGELOG_SESSION_V113.md](CHANGELOG_SESSION_V113.md) |
| Résumé 1-page | [BUGS_SUMMARY.md](BUGS_SUMMARY.md) |
| Détails images | [BUGFIX_IMAGE_DISPLAY.md](BUGFIX_IMAGE_DISPLAY.md) |
| Détails galerie | [BUGFIX_GALLERY_SWAP_MODAL.md](BUGFIX_GALLERY_SWAP_MODAL.md) |
| Détails références | [BUGFIX_REFERENCE_DUPLICATE.md](BUGFIX_REFERENCE_DUPLICATE.md) |

---

## 📈 Statistiques

```
📄 Documents:        11 fichiers
📋 Bugs documentés:  7 bugs
✅ Tous testés:      Yes
🔄 Commits:          9 commits
```

---

## 🎓 Exemple de lecture complète (30 min)

1. **5 min** - README.md
2. **10 min** - CHANGELOG_SESSION_V113.md
3. **5 min** - BUGS_SUMMARY.md
4. **10 min** - 2-3 BUGFIX_*.md spécifiques

---

*Table créée: 22 octobre 2025*
*Pour toute question: consulter docs/README.md*
