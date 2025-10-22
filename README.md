# 🌱 Plant Manager

Application Laravel pour gérer une collection de plantes avec photos, historique de maintenance et système de références automatiques.

**Version:** v1.13 | **Statut:** ✅ Stable

---

## 📚 Documentation

Toute la documentation est organisée et complète dans `docs/`:

### 🚀 Commencer rapidement
- **[� Quick Start](docs/QUICK_START.md)** (2-15 min) - Selon votre besoin
- **[�📖 README complet](docs/README.md)** - Documentation technique

### 📊 Comprendre la session v1.13
- **[🎨 Vue visuelle](docs/VISUAL_SUMMARY.md)** - Résumé graphique
- **[📝 Changelog détaillé](docs/CHANGELOG_SESSION_V113.md)** - Tous les changements
- **[🐛 Résumé bugs](docs/BUGS_SUMMARY.md)** - Les 7 corrections

### 📋 Spécifications & Architecture
- **[📋 Cahiers des charges](docs/SPECIFICATIONS.md)** - v1.01 et v1.02 (historique)

### � Détails des bugs
- **[📋 Table de contents](docs/TABLE_OF_CONTENTS.md)** - Navigation complète
- **[📂 Fiches techniques](docs/)** - 8 fichiers BUGFIX_*.md

### 📦 Anciens fichiers
- **[.archive/](..archive/)** - 30 fichiers archivés (sessions antérieures)

---

## 🚀 Quick Start

```bash
# Installation
composer install
npm install
php artisan migrate --seed

# Development
php artisan serve      # http://localhost:8000

# Build
npm run build
```

---

## ✨ Dernières corrections (v1.13)

✅ **Images:** WebP converties (132 images, -55% taille)
✅ **Galerie:** Swap corrigé, miniatures visibles  
✅ **Lightbox:** Opérationnel en modal
✅ **Références:** Auto-génération sans doublon
✅ **Bouton:** Régénérer fonctionne
✅ **Scripts:** Composants chargés correctement

---

## 📂 Structure docs

```
docs/
├── README.md                    📖 Complet
├── QUICK_START.md              🚀 Rapide
├── TABLE_OF_CONTENTS.md        📋 Navigation
├── VISUAL_SUMMARY.md           🎨 Vue rapide
├── CHANGELOG_SESSION_V113.md   📝 Changements
├── BUGS_SUMMARY.md             🐛 Résumé
├── SPECIFICATIONS.md           📋 Index des specs
├── SPECS_v1_01.md             📋 Spécifications v1.01
├── SPECS_v1_02.md             📋 Spécifications v1.02
└── BUGFIX_*.md (8)            📂 Détails des bugs
```

---

## 🛠️ Commandes

```bash
# Artisan
php artisan serve              # Dev server
php artisan tinker             # Console
php artisan migrate            # DB migrations

# Images  
php artisan images:convert-to-webp                 # Convertir JPG→WebP
php artisan plants:assign-main-photos              # Assigner photos

# Node/Assets
npm run dev                    # Watch mode
npm run build                  # Production build
```

---

## 📞 Navigation rapide

| Je veux... | Lire |
|-----------|------|
| Commencer vite | [Quick Start](docs/QUICK_START.md) |
| Vue d'ensemble | [README](docs/README.md) |
| Résumé 1-page | [Visual Summary](docs/VISUAL_SUMMARY.md) |
| Tous les bugs | [Changelog](docs/CHANGELOG_SESSION_V113.md) |
| Un bug spécifique | [Table of Contents](docs/TABLE_OF_CONTENTS.md) |

---

## ✅ Statut

```
Bugs corrigés:    7 ✅
Tests validés:    7 ✅
Documentation:    100% ✅
Prêt production:  ✅ YES
```

---

*Last updated: 22 octobre 2025 | [Voir docs/](docs/)*

